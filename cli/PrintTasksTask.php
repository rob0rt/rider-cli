<?hh // strict

namespace Rider;

class PrintTasksTask implements TaskInterface {
  use TaskList;

  public function getTaskName(): string {
    return 'tasks';
  }

  public function getDescription(): string {
    return 'Prints the list of available tasks';
  }

  public function run(Vector<string> $args): void {
    print "Available Tasks:\n";
    foreach(self::$tasks as $task) {
      $task_instance = (new \ReflectionClass($task))->newInstance();
      print "\t" . $task_instance->getTaskName() . "\n";
      print "\t\t" . $task_instance->getDescription() . "\n";
    }
  }
}
