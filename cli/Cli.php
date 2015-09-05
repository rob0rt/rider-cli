<?hh // strict

namespace Rider;

class Cli {
  use TaskList;

  public static function run(Vector<string> $args): void {
    $task = self::getTask($args[1]);
    if (!$task) {
      return;
    }

    $task->run($args);
  }

  private static function getTask(string $arg): ?TaskInterface {
    foreach (self::$tasks as $task) {
      $task_instance = (new \ReflectionClass($task))->newInstance();
      if ($task_instance->getTaskName() == $arg) {
        return $task_instance;
      }
    }

    return null;
  }
}
