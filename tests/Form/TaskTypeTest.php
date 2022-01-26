<?php

namespace Tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use DateTime;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testBuildForm(): void
    {
        $formData = [
            'title' => 'test',
            'content' => 'test2'
        ];

        $model = (new Task())->setCreatedAt(new DateTime('10/19/2016 14:48:21'));
        $form = $this->factory->create(TaskType::class, $model);
        $form->submit($formData);

        $expected = (new Task())
            ->setTitle('test')
            ->setContent('test2')
            ->setCreatedAt(new DateTime('10/19/2016 14:48:21'))
        ;
        
        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}