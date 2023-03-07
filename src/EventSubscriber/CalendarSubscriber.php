<?php

namespace App\EventSubscriber;

use App\Repository\CompetitionRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $competitionRepository;
    private $router;

    public function __construct(
        CompetitionRepository $competitionRepository,
        UrlGeneratorInterface $router
    ) {
        $this->competitionRepository = $competitionRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $competitions = $this->competitionRepository
            ->createQueryBuilder('c')
            ->where('c.date_debut BETWEEN :start and :end OR c.date_fin BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($competitions as $competition) {
            // this create the events with your data (here booking data) to fill calendar
            $competitionEvent = new Event(
                $competition->getNom(),
                $competition->getDateDebut(),
                $competition->getDateFin() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $competitionEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $competitionEvent->addOption(
                'url',
                $this->router->generate('app_competition_calendar', [
                    'id' => $competition->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($competitionEvent);
        }
    }
}