<?hh // strict

namespace Rider;

class ModelTask implements TaskInterface {
  public function getTaskName(): string {
    return 'model';
  }

  public function getDescription(): string {
    return 'Provides utilities for creating and building models';
  }

  public function run(Vector<string> $args): void {
    // noop
  }
}
