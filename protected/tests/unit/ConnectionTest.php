<?php

class ConnectionTest extends CDbTestCase {
	public function testDbms() {
		$this->assertEquals("sqlsrv:server=WIBI-PC;database=AdventureWorks", Connection::getDsn(Connection::DBMS_MSSQL, "WIBI-PC", "AdventureWorks"));
		$this->assertEquals("mysql:host=localhost;dbname=wdshop", Connection::getDsn(Connection::DBMS_MYSQL, "localhost", "wdshop"));
		
		print_r(Connection::getDbms());
	}
}

?>