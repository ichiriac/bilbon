<?php
namespace bilbon {
    class Gandalf {
        public static $instances = array('default' => array());
        public static function get( $name ) {
            if ( !isset(self::$instances[$name]) ) throw new \OutOfBoundsException($name);
            return ( ($conf = self::$instances[$name]) instanceof \PDO ) ?
                self::$instances[$name] : self::$instances[$name] = new \PDO(
                    empty($conf['dsn']) ? 'mysql:host=localhost' : $conf['dsn'],
                    empty($conf['user']) ? 'root' : $conf['user'],
                    empty($conf['password']) ? '' : $conf['password'],
                    empty($conf['options']) ? array() : $conf['options']
                )
            ;
        }
    }
    class Hobbit {
        protected $_data = array();
        public static $table;
        public static $storage = 'default';
        public static $primary = 'id';
        public function __construct( array $data = null ) {
            if ( $data ) $this->_data = $data;
        }
        public static function getTable() {
            return ( static::$table ? static::$table : strtolower(get_called_class()));
        }
        public function __get( $property ) {
            return isset($this->_data[$property]) ? $this->_data[$property] : null;
        }
        public function __set( $property, $value ) {
            $this->_data[$property] = $value;
        }
        public function update() {
            $storage = Gandalf::get( static::$storage );
            $replace = array(
                '{table}' => static::getTable(),
                '{primary}' => static::$primary,
                '{sets}' => implode(',', array_map(function( $property, $value ) use($storage) {
                    return $property . '=' . $storage->quote($value);
                }, array_keys($this->_data), array_values( $this->_data))),
                '{value}' => $storage->quote( $this->__get(static::$primary) )
            );
            return $storage->exec(str_replace(array_keys($replace), array_values($replace), 'UPDATE {table} SET {sets} WHERE {primary} = {value}')) > 0;
        }
        public function delete() {
            $replace = array(
                '{table}' => static::getTable(),
                '{primary}' => static::$primary,
                '{value}' => Gandalf::get( static::$storage )->quote( $this->__get(static::$primary) )
            );
            return Gandalf::get( static::$storage )->exec(str_replace(array_keys($replace), array_values($replace), 'DELETE FROM {table} WHERE {primary} = {value}')) > 0;
        }
        public function insert() {
            $replace = array(
                '{table}' => static::getTable(),
                '{properties}' => implode(',', array_keys($this->_data)),
                '{values}' => implode(',', array_map(array(Gandalf::get( static::$storage ), 'quote'), array_values($this->_data)))
            );
            $this->__set(
                static::$primary,
                Gandalf::get( static::$storage )->exec(str_replace(array_keys($replace), array_values($replace), 'INSERT INTO {table} ({properties}) VALUES ({values})')) > 0 ?
                    Gandalf::get( static::$storage )->lastInsertId() : null
            );
        }
        public static function request( $sql, array $params = null ) {
            $replace = $params ? array_map( array(Gandalf::get( static::$storage ), 'quote'), $params) : array();
            $replace['{table}'] = static::getTable();
            return ($result = Gandalf::get(static::$storage)->query(
                str_replace(array_keys($replace), array_values($replace), $sql), \PDO::FETCH_CLASS, get_called_class()
            )) ? $result->fetchAll() : array();
        }
        public function __toString() {
             return static::$storage . '.' . static::$table . '@' . get_class($this) . " {\n" . implode("\n", array_map(function( $property, $value ) {
                 return ' '. str_pad($property, 8) . ': ' . (strlen($value) > 60 ? substr($value, 0, 60) . '...' : $value);
             }, array_keys($this->_data), array_values($this->_data))) . "\n}\n";
        }
    }
}