<?hh

namespace Facebook\HackCodegen;

class ModelGeneratorTask extends \Robo\Task\BaseTask implements \Robo\Contract\TaskInterface {
  public function run(): \Robo\Result {
    $this->printTaskInfo('Generating Models');
    foreach(glob('models/*.php') as $file) {
      $classes = self::getClassesFromFile($file);
      foreach($classes as $class) {
        $schema = new $class();
        if ($schema instanceof \ModelSchema) {
          $this->generate($schema);
        }
      }
    }

    $this->printTaskSuccess("Finished Generating Models");
    return \Robo\Result::success($this, "Finished Generating Models");
  }

  private function generate(\ModelSchema $schema): void {
    $this->printTaskInfo('Generating Models');

    $class = codegen_class(self::getSchemaName($schema))
      ->setIsFinal();

    foreach ($schema->getFields() as $name => $field) {
      $var = codegen_member_var($name)
        ->setProtected()
        ->setType($field->getType());

      switch($field->getType()) {
      case 'string':
        $var->setValue('');
        break;
      case 'DateTime':
        $var->setLiteralValue('new DateTime()');
        break;
      }

      $class->addVar($var);
    }

    $class
      ->addMethod(self::getGen($schema))
      ->addMethods(self::getGetters($schema));

    codegen_file(getcwd() . '/build/' . self::getSchemaName($schema) . '.php')
      ->addClass($class)
      ->setGeneratedFrom(codegen_generated_from_script())
      ->save();
  }

  private static function getSchemaName(\ModelSchema $schema): string {
    $name = get_class($schema);
    return Str::endsWith($name, 'Schema')
      ? Str::substr($name, 0, -6)
      : $name;
  }

  private static function getGen(\ModelSchema $schema): CodegenMethod {
    $sql = 'SELECT * FROM ' .  $schema->getTableName() .
      ' WHERE ' . $schema->getIdField() . '=$id';

    $body = hack_builder()
      ->addLine('$query = DB::queryFirstRow(\'' . $sql . '\');')
      ->startIfBlock('!$query')
      ->addReturn('null')
      ->endIfBlock()
      ->addLine('$res = new ' . self::getSchemaName($schema) . '();');

    foreach ($schema->getFields() as $name => $field) {
      $body->addLine('$res->' . $name . ' = $query[\'' . $field->getDbColumn() . '\'];');
    }

    $body->addReturn('$res');

    return codegen_method('genByID')
      ->setIsStatic()
      ->addParameter('int $id')
      ->setReturnType('?' . self::getSchemaName($schema))
      ->setBody($body->getCode());
  }

  private static function getGetters(\ModelSchema $schema): Vector<CodegenMethod> {
    $methods = Vector {};
    foreach ($schema->getFields() as $name => $field) {
      $return_type = $field->getType();
      $data = '$this->' . $name;

      if ($field->isOptional()) {
        $return_type = '?' . $return_type;
        $builder = hack_builder();
        $builder->addWithSuggestedLineBreaks(
          "return isset($data)\t? $data\t: null;",
        );
        $body = $builder->getCode();
      } else {
        $body = 'return ' . $data . ';';
      }

      $methods[] = codegen_method('get'.$name)
        ->setReturnType($return_type)
        ->setBody($body);
    }

    return $methods;
  }

  private static function getClassesFromFile(string $file): Vector<string> {
    $classes = Vector {};
    $tokens = token_get_all(file_get_contents($file));
    $count = count($tokens);
    for ($i = 2; $i < $count; $i++) {
      if ($tokens[$i - 2][0] == T_CLASS &&
          $tokens[$i - 1][0] == T_WHITESPACE &&
          $tokens[$i][0] == T_STRING
      ) {
          $class_name = $tokens[$i][1];
          $classes[] = $class_name;
      }
    }
    return $classes;
  }
}
