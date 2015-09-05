<?hh // strict

namespace Rider;

interface TaskInterface {
  public function getTaskName(): string;
  public function getDescription(): string;
  public function run(Vector<string> $args): void;
}
