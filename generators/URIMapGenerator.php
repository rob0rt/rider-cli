<?hh // strict

namespace Rider;

class URIMapGenerator {
  public function run(): void {
    $routes = URIMapGenerator::getRoutesMap();
    $map = var_export($routes, true);
    $template = "<?hh\nreturn ".str_replace("HH\\", "", $map).";";
    file_put_contents('build/URIMap.php', $template);
  }

  private static function getRoutesMap(): Map<string, string> {
    // Get all the php files in the cwd
    $directory = new \RecursiveDirectoryIterator(getcwd().'/controllers');
    $iterator = new \RecursiveIteratorIterator($directory);
    $files = new \RegexIterator(
      $iterator,
      '/^.+(\.php|\.hh)$/i',
      \RegexIterator::MATCH,
      \RegexIterator::USE_KEY,
    );
    // Get the paths from the attributes
    $path_map = Map {};
    foreach ($files as $file) {
      $paths = self::getPathsFromFile($file->getPathname());
      if ($paths) {
        $path_map->addAll($paths->items());
      }
    }
    return $path_map;
  }

  private static function getPathsFromFile(string $file): Map<string, string> {
    $paths = Map {};
    $classes = self::getClassesFromFile($file);
    foreach ($classes as $class) {
      $controller = new \ReflectionClass($class);
      if ($controller->isSubclassOf(BaseController::class)) {
        $paths[$controller->getMethod('getPath')->invoke(null)] = $class;
      }
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
          $tokens[$i][0] == T_STRING) {
        $class_name = $tokens[$i][1];
        $classes[] = $class_name;
      }
    }
    return $classes;
  }
}
