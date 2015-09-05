<?hh // strict

namespace Rider;

interface ModelSchema {
  public function getFields(): Map<string, ModelField>;
  public function getDsn(): string;
  public function getTableName(): string;
  public function getIdField(): string;
}
