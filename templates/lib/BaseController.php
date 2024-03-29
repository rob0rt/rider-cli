<?hh // strict

namespace Rider;

abstract class BaseController {
  abstract public static function getPath(): string;

  public static function getConfig(): ControllerConfig {
    return (new ControllerConfig());
  }
}
