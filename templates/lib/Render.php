<?hh // strict

class Rider\Render {
  public static function go(:xhp $content, ?string $controller): void {
    print
      <rider:layout js-files={Rider\JS::get_required()} css-files={Rider\CSS::get_required()}>
        {$content}
      </rider:layout>;
  }
}
