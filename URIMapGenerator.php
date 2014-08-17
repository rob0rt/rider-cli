<?hh

class URIMapGenerator {
  public static function getRoutesMap(): Map<string, string> {
    // Get all the php files in the cwd
    $directory = new RecursiveDirectoryIterator(getcwd());
    $iterator = new RecursiveIteratorIterator($directory);
    $files = new RegexIterator(
      $iterator,
      '/^.+(\.php|\.hh)$/i',
      RecursiveRegexIterator::GET_MATCH
    );

    // Get the paths from the attributes
    $path_map = Map {};
    foreach ($files as $file) {
      $paths = self::getPathsFromFile($file[0]);
      foreach ($paths as $path) {
        if (isset($path[0])) {
          $path_map[$path[0]] = $file[0];
        }
      }
    }
    return $path_map;
  }

  private static function getPathsFromFile(string $file): Vector<string> {
    $paths = Vector {};
    require_once($file);
    $classes = self::getClassesFromFile($file);
    foreach ($classes as $class) {
      // Instantiate the classes in the file via Reflection to get the path
      $reflection_class = new ReflectionClass($class);
      $paths[] = $reflection_class->getAttribute('path');
    }
    return $paths;
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

$routes = URIMapGenerator::getRoutesMap();
$file = file_get_contents(__DIR__ . '/templates/MapTemplate.txt');
$file = str_replace('{{CONTENTS}}', var_export($routes, true), $file);
file_put_contents('build/URIMap.php', $file);
