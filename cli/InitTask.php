<?hh // strict

namespace Rider;

class InitTask implements TaskInterface {
  public function getTaskName(): string {
    return 'init';
  }

  public function getDescription(): string {
    return 'Initializes the current directory as a Rider project';
  }

  public function run(Vector<string> $args): void {
    // noop
  }
}
