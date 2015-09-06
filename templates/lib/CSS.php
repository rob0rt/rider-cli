<?hh // strict

namespace Rider;

class CSS {
  private static Vector<string> $files = Vector{};

  public static function require_css(string $file): void {
    self::$files->add($file);
  }

  public static function get_required(): Vector<string> {
    return self::$files;
  }
}
