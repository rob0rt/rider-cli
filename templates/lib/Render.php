<?hh // strict

namespace Rider;

use :rider:layout;

class Render {
  public static function go(\XHPRoot $content, ?string $controller): void {
    print
      <rider:layout js-files={JS::get_required()} css-files={CSS::get_required()}>
        {$content}
      </rider:layout>;
  }
}
