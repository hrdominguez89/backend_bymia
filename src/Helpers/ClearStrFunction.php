<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 *
 */

namespace App\Helpers;

use Doctrine\ORM\Query\Lexer;

/**
 * "ClearStr" "(" StringPrimary, {"," SimpleArithmeticExpression }* ")"
 *
 * Esta funcion permite limpiar un string de caracteres quitando las tildes y
 * llevando los caracteres a minusculas es util para hacer comparacion de cadenas
 * sin que se tengan en cuenta en la comparacion las tildes y las mayusculas
 * ej: clearstr('Función') = 'funcion'
 *
 * tambien puede usarse para valores numericos pasando el parametro opcional 1, y se
 * devolveria el mismo numero como cadena de caracteres
 * ej clearstr(1234,1) = '1234'
 *
 * @author Pedro Frank Cadenas del Llano
 */
class ClearStrFunction extends \Doctrine\ORM\Query\AST\Functions\FunctionNode
{
    public $firstString;

    public $filtro = '';

    /**
     * @override
     */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        $str = $sqlWalker->walkStringPrimary($this->firstString);
        switch ($sqlWalker->walkSimpleArithmeticExpression($this->filtro)) {
            case 1:
                $filtro = '::text';
                break;
            default:
                $filtro = '';
        }
        //La funcion unaccent es una extencion del postgres que hay que agregar a la base de datos
        // return sprintf("lower(unaccent(%s$filtro))", $str);
        //Otra modo de hacer lo mismo sin usar unaccent
        return "lower(translate($str$filtro,'áéíóúñÑÁÉÍÓÚäëïöüÄËÏÖÜ','aeiounNAEIOUaeiouAEIOU'))";
    }

    /**
     * @override
     */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->firstString = $parser->StringPrimary();

        while ($parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->filtro = $parser->SimpleArithmeticExpression();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}

