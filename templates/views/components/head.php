<?hh // strict

final class :rider:head extends :x:element {
  attribute
    string title = "New Project",
    Vector<string> js-files,
    Vector<string> css-files;

  final protected function render(): :head {
    $js_files = Vector {};
    foreach ($this->getAttribute('js-files') as $js_file) {
      $js_files[] = <script src={"/js/$js_file.js"}></script>;
    }

    $css_files = Vector {};
    foreach ($this->getAttribute('css-files') as $css_file) {
      $css_files[] =
        <link rel="stylesheet" type="text/css" href={"/css/$css_file.css"} />;
    }

    return
      <head>
        <title>{$this->getAttribute('title')}</title>
        {$css_files}
        <script
          src=
            "https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js">
        </script>
        <script
          src=
            "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js">
        </script>
        <script
          src=
            "https://cdnjs.cloudflare.com/ajax/libs/list.js/1.1.1/list.min.js">
        </script>
        {$js_files}
      </head>;
  }
}
