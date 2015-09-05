<?hh // strict

namespace Rider;

trait TaskList {
  private static Vector<string> $tasks = Vector {
    PrintTasksTask::class,
    InitTask::class,
    ModelTask::class,
  };
}
