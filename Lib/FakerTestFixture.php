<?php
App::uses('Populator', 'CakeFaker.Lib/Faker/ORM/CakePHP');

class FakerTestFixture extends CakeTestFixture
{
    protected $generator;
    protected $populator;
    protected $seed;

    /**
     * FakerTestFixture constructor.
     */
    public function __construct()
    {
        $this->generator = \Faker\Factory::create();
        if ($this->seed = Configure::read('faker.seed')) {
        }
        if (!ClassRegistry::isKeySet('faker')) {
            ClassRegistry::addObject('faker', new Populator($this->generator));
        }
    }

    public function insert($db)
    {
        $this->generate();
    }

    /**
     * Use this to generate test records.
     * Built this to use outside of test fixtures for on the fly test data generation.
     *
     * @param string $modelName CakePHP 2 model name.
     * @param int $numRecords Number of records to generate.
     * @param array|null $alterFields An array of column names with provided values.
     * @param bool $appendRecords Pass true if you just want to add more records rather than destroy records.
     */
    public function generate($modelName = null, $numRecords = null, array $alterFields = null, $appendRecords = false)
    {
        // Configure faker.
        $this->generator->seed($this->seed);
        $this->populator = ClassRegistry::getObject('faker');

        // Set up the params.
        $modelName = is_null($modelName) ? $this->model_name : $modelName;
        $numRecords = is_null($numRecords) ? $this->num_records : $numRecords;
        $alterFields = is_null($alterFields) ? $this->alterFields($this->generator) : $alterFields;

        // Clean out previous saved data.
        if (!$appendRecords) {
            $model = ClassRegistry::init($modelName);
            $model->deleteAll();
        }

        // Populate the table.
        $this->populator->addEntity($modelName, $numRecords, $alterFields);
        $this->populator->execute();
    }

    /**
     * @return \Faker\Generator
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * @return mixed
     */
    public function getPopulator()
    {
        return $this->populator;
    }

    /**
     * @return mixed
     */
    public function getSeed()
    {
        return $this->seed;
    }

    /**
     * @param mixed $seed
     */
    public function setSeed($seed)
    {
        $this->seed = $seed;
    }

    protected function alterFields($generator)
    {
        return array();
    }
}
