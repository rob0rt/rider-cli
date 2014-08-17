<?hh

class AutoloadMapGenerator {
  public static function getClassMap(): Map<string, string> {
    // Get all the php files in the cwd
    $directory = new RecursiveDirectoryIterator(getcwd());
    $iterator = new RecursiveIteratorIterator($directory);
    $files = new RegexIterator(
      $iterator,
      '/^.+\.php$/i',
      RecursiveRegexIterator::GET_MATCH
    );

    // Get the paths from the attributes
    $path_map = Map {};
    foreach ($files as $file) {
      $classes = self::getClassesFromFile($file[0]);
      foreach ($classes as $class) {
        $path_map[$class] = $file[0];
      }
    }
    return $path_map;
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

AutoloadMapGenerator::getClassMap();
