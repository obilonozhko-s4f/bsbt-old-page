<?php
/*
 * $Id: Schema.php 1838 2007-06-26 00:58:21Z nicobn $
 *
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
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

/**
 * Doctrine_Import_ManagersSchema
 *
 * Class for importing Doctrine_Record classes from a yaml schema definition
 *
 * @package     Doctrine
 * @subpackage  Import
 * @link        www.doctrine-project.org
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version     $Revision: 1838 $
 * @author      Nicolas Bérard-Nault <nicobn@gmail.com>
 * @author      Jonathan H. Wage <jonwage@gmail.com>
 */
class Doctrine_Import_ManagersSchema extends Doctrine_Import_Schema
{

  /**
   * overridden
   * @see lib/database/Doctrine/Import/Doctrine_Import_Schema::importSchema()
   */
  public function importSchema($schema, $format = 'yml', $directory = null, $models = array())
  {
    
    $schema = (array) $schema;
    $builder = new Doctrine_Import_ManagersBuilder();
    
    $builder->setTargetPath($directory);
    $builder->setOptions($this->getOptions());

    $array = $this->buildSchema($schema, $format);

    if (count($array) == 0) {
      throw new Doctrine_Import_Exception(
      sprintf('No ' . $format . ' schema found in ' . implode(", ", $schema))
      );
    }

    foreach ($array as $name => $definition) {
      if ( ! empty($models) && !in_array($definition['className'], $models)) {
        continue;
      }
      
      $definition['className'] .= 'Manager';
      $definition['generate_once'] = true;
      $builder->buildRecord($definition);
    }
  }
}