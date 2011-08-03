<?php

/**
 * Xi
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category Xi
 * @package  Zend
 * @license  http://www.opensource.org/licenses/BSD-3-Clause New BSD License
 */

namespace Xi\Zend\Auth\Adapter;

use Xi\Zend\Auth\Adapter\AbstractAdapter,
    Xi\Zend\Auth\Condition\DoctrineORMCondition,
    Zend_Auth_Adapter_Interface as AuthAdapter,
    Zend_Auth_Result,
    Doctrine\ORM\EntityManager,
    Doctrine\ORM\NoResultException,
    Doctrine\ORM\NonUniqueResultException;

/**
 * Zend Framework authentication adapter for Doctrine 2+ ORM
 *
 * @category   Xi
 * @package    Zend
 * @subpackage Auth
 * @author     Mikko Hirvonen <mikko.petteri.hirvonen@gmail.com>
 */
class DoctrineORMAdapter extends AbstractAdapter implements AuthAdapter
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Entity class to select from
     *
     * @var string
     */
    private $entityClass;

    /**
     * Entity class alias to be used in DQL
     *
     * @var string
     */
    private $entityAlias;

    /**
     * This columns value will be used as the identity when creating an
     * Zend_Auth_Result
     *
     * @var string
     */
    private $identityColumn;

    /**
     * Query conditions
     *
     * An array of column => value pairs. Value can be a string or an instance
     * of DoctrineORMCondition.
     *
     * @var array
     */
    private $conditions;

    /**
     * @var Doctrine\ORM\QueryBuilder
     */
    private $queryBuilder;

    /**
     * @param  EntityManager   $em
     * @param  string          $entityClass
     * @param  string          $entityAlias
     * @param  string          $identityColumn
     * @param  array           $conditions
     * @return DoctrineAdapter
     */
    public function __construct(EntityManager $em, $entityClass, $entityAlias,
        $identityColumn, array $conditions = array()
    ) {
        $this->em             = $em;
        $this->entityClass    = $entityClass;
        $this->entityAlias    = $entityAlias;
        $this->identityColumn = $identityColumn;

        $this->addConditions($conditions);
    }

    /**
     * Performs an authentication attempt
     *
     * Implements Zend_Auth_Adapter_Interface::authenticate
     *
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $query = $this->getQuery();

        try {
            return $this->createResult(
                Zend_Auth_Result::SUCCESS,
                $query->getSingleScalarResult()
            );
        } catch (NoResultException $e) {
            return $this->createResult(
                Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                array('A record with the supplied identity could not be found.')
            );
        } catch (NonUniqueResultException $e) {
            return $this->createResult(
                Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS,
                null,
                array('More than one record matches the supplied identity.')
            );
        }
    }

    /**
     * @param  array              $conditions
     * @return DoctrineORMAdapter
     */
    public function addConditions(array $conditions)
    {
        foreach ($conditions as $key => $condition) {
            $this->addCondition($key, $condition);
        }

        return $this;
    }

    /**
     * @param  string                      $key
     * @param  string|DoctrineORMCondition $condition
     * @return DoctrineORMAdapter
     */
    public function addCondition($key, $condition)
    {
        $this->conditions[$key] = $condition;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @return string
     */
    public function getEntityAlias()
    {
        return $this->entityAlias;
    }

    /**
     * @return string
     */
    public function getIdentityColumn()
    {
        return $this->identityColumn;
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @return Doctrine\ORM\QueryBuilder
     */
    private function getQueryBuilder()
    {
        if ($this->queryBuilder === null) {
            $this->queryBuilder = $this->em->createQueryBuilder();
        }

        return $this->queryBuilder;
    }

    /**
     * @return Doctrine\ORM\Query
     */
    private function getQuery()
    {
        $qb = $this->getQueryBuilder();
        $qb->select(sprintf('%s.%s',
                            $this->getEntityAlias(),
                            $this->getIdentityColumn()))
           ->from($this->getEntityClass(), $this->getEntityAlias());

        foreach ($this->getConditions() as $key => $condition) {
            $this->addQueryCondition($key, $condition);
        }

        return $qb->getQuery();
    }

    /**
     * @param  string                      $key
     * @param  string|DoctrineORMCondition $condition
     * @return DoctrineORMAdapter
     */
    private function addQueryCondition($key, $condition)
    {
        $qb = $this->getQueryBuilder();

        if ($condition instanceof DoctrineORMCondition) {
            $qb->andWhere($this->getWhereClause($key,
                                                $condition->getCondition()));

            foreach ($condition->getParameters() as $key => $value) {
                $qb->setParameter($key, $value);
            }
        } else {
            $qb->andWhere($this->getWhereClause($key, ':' . $key))
               ->setParameter($key, $condition);
        }

        return $this;
    }

    /**
     * @param  string $column
     * @param  string $value
     * @return string
     */
    private function getWhereClause($column, $value)
    {
        return sprintf('%s.%s = %s', $this->getEntityAlias(), $column, $value);
    }
}
