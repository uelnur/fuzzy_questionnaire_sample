<?php

namespace App\View\Pages\Homepage;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class StartSessionForm extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder->add('create', SubmitType::class, [
            'label' => 'Начать новую сессию',
        ]);
    }
}
