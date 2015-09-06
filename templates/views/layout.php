<?hh // strict

final class :rider:layout extends :x:element {
  attribute Vector<string> js-files, Vector<string> css-files;

  children (:xhp);

  final protected function render(): XHPRoot {
    return
      <html>
        <rider:head
          js-files={$this->getAttribute('js-files')}
          css-files={$this->getAttribute('css-files')}
        />
        <body>
          {$this->getChildren()}
        </body>
      </html>;
  }
}
