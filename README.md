# Xi Zend

General component outline
-------------------------

Xi\Storage: Simple read / write storage capabilities with Zend_Auth_Storage_Interface compatibility.
Xi\Zend\Auth: Authentication adapters for Zend Framework. Contains only Doctrine 2+ ORM authentication adapter for now.

### Xi\Zend\Auth

Usage for Doctrine ORM adapter:

        // Conditions for the query to match
        $conditions = array(
            // Simple condition
            'email' => 'foobar@example.com',

            // Complex conditions can be expressed with a Condition class
            'password' => new Xi\Zend\Auth\Condition\DoctrineORMCondition('SHA1(CONCAT(:applicationSalt, CONCAT(:password, u.passwordSalt)))', array(
                'applicationSalt' => 'my secret application salt',
                'password'        => 'user password',
            )),
        );

        $adapter = new Xi\Zend\Auth\Adapter\DoctrineORMAdapter(
            $em,            // $em instanceof Doctrine\ORM\EntityManager
            'Entity\User',  // Entity class to select from
            'u',            // Entity class alias to be used in query
            'email',        // Name of the column whose value will be used as the identity when creating Zend_Auth_Result
            $conditions
        );
