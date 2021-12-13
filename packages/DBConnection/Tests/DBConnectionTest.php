<?php

namespace App\DBConnection\Tests;

use App\DBConnection\DBConnection;
use App\DBConnection\DBConnectionConfigDTO;
use App\DBConnection\DBException;
use App\DBConnection\MysqliWrapper;
use mysqli_result;
use PHPUnit\Framework\TestCase;

class DBConnectionTest extends TestCase
{
    protected DBConnectionConfigDTO $dBConnectionConfigDTO;
    protected MysqliWrapper $mysqliWrapper;

    protected function setUp(): void
    {
        $this->mysqliWrapper = $this->createMock(MysqliWrapper::class);
        $this->dBConnectionConfigDTO = $this->createMock(DBConnectionConfigDTO::class);
    }


    public function constructProvider(): array
    {
        return [
            'success connection' => [
                new DBConnectionConfigDTO([
                    'host' => 'a',
                    'username' => 'b',
                    'password' => 'c',
                    'database' => 'd',
                    'port' => 1
                ]),
                false,
                null
            ],
            'error connection' => [
                new DBConnectionConfigDTO([
                    'host' => 'a',
                    'username' => 'b',
                    'password' => 'c',
                    'database' => 'd',
                    'port' => 1
                ]),
                true,
                DBException::class
            ]
        ];
    }

    /**
     * @covers \App\DBConnection\DBConnection::__construct
     * @dataProvider constructProvider
     */
    public function testConstruct(DBConnectionConfigDTO $configDTO, bool $errnoResult, ?string $expectedException): void
    {
        $this->mysqliWrapper
            ->expects($this->once())
            ->method('connect')
            ->with('a', 'b', 'c', 'd', 1);

        $this->mysqliWrapper
            ->expects($this->once())
            ->method('isConnectError')
            ->will($this->returnValue($errnoResult));

        if ($expectedException) {
            $this->expectException($expectedException);
        }

        new DBConnection($this->mysqliWrapper, $configDTO);
    }


    public function insertProvider(): array
    {
        return [
            'normal insert' => [
                'tablename',
                [
                    'int' => 1,
                    'float' => 1.123,
                    'string' => 'asdasd',
                    'numeric' => '123'
                ],
                "INSERT INTO tablename (`int`,`float`,`string`,`numeric`) VALUES (1,1.123,'asdasd','123')",
                true,
                null
            ],
            'insert query error' => [
                'tablename2',
                ['a' => 1, 'numeric' => '123'],
                "INSERT INTO tablename2 (`a`,`numeric`) VALUES (1,'123')",
                false,
                DBException::class
            ],
        ];
    }

    /**
     * @covers \App\DBConnection\DBConnection::insert
     * @dataProvider insertProvider
     */
    public function testInsert(string $tableName, array $data, string $expectedQuery, bool $queryResult, $expectedException): void
    {
        $dbConnection = new DBConnection($this->mysqliWrapper, $this->dBConnectionConfigDTO);

        $this->mysqliWrapper
            ->expects($this->once())
            ->method('query')
            ->with($expectedQuery)
            ->will($this->returnValue($queryResult));


        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $this->mysqliWrapper
            ->method('isConnectError')
            ->with()
            ->will($this->returnValue(1));

        $dbConnection->insert($tableName, $data);
    }


    public function selectProvider(): array
    {
        return [
            'normal select' => [
                'tablename',
                's<>\'asdads\'',
                "SELECT * FROM tablename WHERE s<>'asdads'",
                $this->createMock(mysqli_result::class),
                null,
            ],
            'select query error' => [
                'tablename2',
                null,
                "SELECT * FROM tablename2 ",
                false,
                DBException::class,
            ],
        ];
    }

    /**
     * @covers \App\DBConnection\DBConnection::select
     * @dataProvider selectProvider
     */
    public function testSelect(string $tableName, ?string $where, string $expectedQuery, $queryResult, $expectedException): void
    {
        $dbConnection = new DBConnection($this->mysqliWrapper, $this->dBConnectionConfigDTO);

        $this->mysqliWrapper
            ->expects($this->once())
            ->method('query')
            ->with($expectedQuery)
            ->will($this->returnValue($queryResult));

        if ($expectedException) {
            $this->expectException($expectedException);
        }

        if ($queryResult) {
            $queryResult
                ->expects($this->once())
                ->method('fetch_all')
                ->with(MYSQLI_ASSOC)
                ->will($this->returnValue([]));
        }



        $dbConnection->select($tableName, $where);
    }


    public function updateProvider(): array
    {
        return [
            'normal update' => [
                'tablename',
                122,
                [
                    'int' => 1,
                    'float' => 1.123,
                    'string' => 'asdasd',
                    'numeric' => '123'
                ],
                "UPDATE tablename SET `int`=1, `float`=1.123, `string`='asdasd', `numeric`='123' WHERE id=122",
                true,
                null,
            ],
            'update query error' => [
                'tablename',
                9,
                ['a' => 1],
                "UPDATE tablename SET `a`=1 WHERE id=9",
                false,
                DBException::class,
            ],
        ];
    }

    /**
     * @covers \App\DBConnection\DBConnection::update
     * @dataProvider updateProvider
     */
    public function testUpdate(
        string $tableName,
        int $id,
        array $data,
        string $expectedQuery,
        bool $queryResult,
        ?string $expectedException
    ): void {
        $dbConnection = new DBConnection($this->mysqliWrapper, $this->dBConnectionConfigDTO);

        $this->mysqliWrapper
            ->expects($this->once())
            ->method('query')
            ->with($expectedQuery)
            ->will($this->returnValue($queryResult));

        if ($expectedException) {
            $this->expectException($expectedException);
        }

        $this->assertEquals($queryResult, $dbConnection->update($tableName, $id, $data));

    }

}