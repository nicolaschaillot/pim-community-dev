<?php

namespace spec\Pim\Bundle\EnrichBundle\Connector\Reader\MassEdit;

use Akeneo\Component\Batch\Model\JobExecution;
use Akeneo\Component\Batch\Model\StepExecution;
use PhpSpec\ObjectBehavior;
use Pim\Component\Catalog\Model\FamilyInterface;
use Pim\Component\Catalog\Repository\FamilyRepositoryInterface;
use Pim\Component\Connector\Model\JobConfigurationInterface;
use Pim\Component\Connector\Repository\JobConfigurationRepositoryInterface;

class FilteredFamilyReaderSpec extends ObjectBehavior
{
    function let(JobConfigurationRepositoryInterface $jobConfigurationRepo, FamilyRepositoryInterface $familyRepository)
    {
        $this->beConstructedWith($jobConfigurationRepo, $familyRepository);
        $this->setConfiguration(
            [
                'filters' => [
                    [
                        'field'    => 'id',
                        'operator' => 'IN',
                        'value'    => [12, 13, 14]
                    ]
                ]
            ]
        );
    }

    function it_reads_families(
        $familyRepository,
        StepExecution $stepExecution,
        JobExecution $jobExecution,
        JobConfigurationInterface $jobConfiguration,
        FamilyInterface $pantFamily,
        FamilyInterface $sockFamily
    ) {
        $stepExecution->getJobExecution()->willReturn($jobExecution);
        $families = [$pantFamily, $sockFamily];
        $familyRepository->findByIds([12, 13, 14])->willReturn($families);
        $stepExecution->incrementSummaryInfo('read')->shouldBeCalled();

        $this->setStepExecution($stepExecution);

        $this->read()->shouldReturn($pantFamily);
        $this->read()->shouldReturn($sockFamily);
        $this->read()->shouldReturn(null);

        $stepExecution->incrementSummaryInfo('read')->shouldHaveBeenCalledTimes(2);
    }
}
