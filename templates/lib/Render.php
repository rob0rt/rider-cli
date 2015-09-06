<?hh // strict

class Render {
  public static function go(:xhp $content, ?string $controller): void {
    print
      <rider:layout js-files={JS::get_required()} css-files={CSS::get_required()}>
        {$content}
      </rider:layout>;
  }
}
