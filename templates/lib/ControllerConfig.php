<?hh // strict

namespace Rider;

class ControllerConfig {
  private string $title = '';
  private bool $requires_login = false;

  public function setPageTitle(string $title): void {
    $this->title = $title;
  }

  public function getPageTitle(): string {
    return $this->title;
  }

  public function requiresLogin(): void {
    $this->requires_login = true;
  }

  public function doesRequireLogin(): bool {
    return $this->requires_login;
  }
}
