<?hh // strict

class JS {
  private static Vector<string> $files = Vector{};

  public static function require_js(string $file): void {
    self::$files->add($file);
  }

  public static function get_required(): Vector<string> {
    return self::$files;
  }
}
