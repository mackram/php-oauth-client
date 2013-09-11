<?php

/**
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace fkooman\OAuth\Client;

class ScopeTest extends \PHPUnit_Framework_TestCase
{

    public function testStringScope()
    {
        $s = new Scope("read write delete");
        $this->assertTrue($s->hasScope(new Scope("read")));
        $this->assertTrue($s->hasScope(new Scope("write")));
        $this->assertTrue($s->hasScope(new Scope("delete")));
        $this->assertTrue($s->hasScope(new Scope(array("read", "delete"))));
        $this->assertFalse($s->hasScope(new Scope("foo")));
    }

    public function testArrayScope()
    {
        $s = new Scope(array("read", "write", "delete"));
        $this->assertTrue($s->hasScope(new Scope("read")));
        $this->assertTrue($s->hasScope(new Scope("write")));
        $this->assertTrue($s->hasScope(new Scope("delete")));
        $this->assertTrue($s->hasScope(new Scope(array("read", "delete"))));
        $this->assertFalse($s->hasScope(new Scope("foo")));
    }

    public function testNullScope()
    {
        $s = new Scope(null);
        $this->assertFalse($s->hasScope(new Scope("foo")));
        $this->assertTrue($s->hasScope(new Scope(array())));
        $this->assertTrue($s->hasScope(new Scope()));
        $this->assertTrue($s->hasScope(new Scope(array())));

    }

    /**
     * @expectedException \fkooman\OAuth\Client\ScopeException
     * @expectedExceptionMessage invalid scope token 'ç'
     */
    public function testInvalidScope()
    {
        $s = new Scope("ç");
    }

    /**
     * @expectedException \fkooman\OAuth\Client\ScopeException
     * @expectedExceptionMessage invalid scope token 'ç'
     */
    public function testInvalidScopeArray()
    {
        $s = new Scope(array("foo", "bar", "baz", "ç"));
    }
}
