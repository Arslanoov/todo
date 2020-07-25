<?php

declare(strict_types=1);

namespace Domain\Todo\Entity\Schedule\Task;

use Cycle\Annotated\Annotation as Cycle;
use Domain\Todo\Entity\Schedule\Schedule;

/**
 * Class Task
 * @Cycle\Entity(
 *     table="todo_schedule_tasks",
 *     role="task"
 * )
 * @Cycle\Table()
 */
final class Task
{
    /**
     * @Cycle\Column(type="primary", primary=true)
    /** @Cycle\Relation\Embedded(target="TaskId") */
    private TaskId $id;
    /**
     * @Cycle\Column(type="string")
     * @Cycle\Relation\BelongsTo(target="schedule", innerKey="schedule", outerKey="id")
     */
    private Schedule $schedule;
    /** @Cycle\Relation\Embedded(target="Name") */
    private Name $name;
    /** @Cycle\Relation\Embedded(target="Description") */
    private Description $description;
    /** @Cycle\Relation\Embedded(target="ImportantLevel") */
    private ImportantLevel $level;
    /** @Cycle\Relation\Embedded(target="TaskStatus") */
    private TaskStatus $status;

    /**
     * Task constructor.
     * @param TaskId $id
     * @param Schedule $schedule
     * @param Name $name
     * @param Description $description
     * @param ImportantLevel $level
     * @param TaskStatus $status
     */
    private function __construct(
        TaskId $id, Schedule $schedule, Name $name,
        Description $description, ImportantLevel $level, TaskStatus $status
    )
    {
        $this->id = $id;
        $this->schedule = $schedule;
        $this->name = $name;
        $this->description = $description;
        $this->level = $level;
        $this->status = $status;
    }

    public static function new(
        Schedule $schedule, Name $name,
        Description $description, ImportantLevel $level
    ): self
    {
        return new self(
            TaskId::uuid4(),
            $schedule,
            $name,
            $description,
            $level,
            TaskStatus::notComplete()
        );
    }

    /**
     * @return TaskId
     */
    public function getId(): TaskId
    {
        return $this->id;
    }

    /**
     * @return Schedule
     */
    public function getSchedule(): Schedule
    {
        return $this->schedule;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return Description
     */
    public function getDescription(): Description
    {
        return $this->description;
    }

    /**
     * @return ImportantLevel
     */
    public function getLevel(): ImportantLevel
    {
        return $this->level;
    }

    /**
     * @return TaskStatus
     */
    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function isNotImportant(): bool
    {
        return $this->getLevel()->isNotImportant();
    }

    public function isImportant(): bool
    {
        return $this->getLevel()->isImportant();
    }

    public function isVeryImportant(): bool
    {
        return $this->getLevel()->isVeryImportant();
    }

    public function isNotComplete(): bool
    {
        return $this->getStatus()->isNotComplete();
    }

    public function isInProgress(): bool
    {
        return $this->getStatus()->isInProgress();
    }

    public function isComplete(): bool
    {
        return $this->getStatus()->isComplete();
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function changeDescription(Description $description): void
    {
        $this->description = $description;
    }

    public function changeSchedule(Schedule $schedule): void
    {
        $this->schedule = $schedule;
    }

    public function stopExecution(): void
    {
        $this->status = TaskStatus::notComplete();
    }

    public function startExecution(): void
    {
        $this->status = TaskStatus::inProgress();
    }

    public function complete(): void
    {
        $this->status = TaskStatus::complete();
    }

    public function makeNotImportant(): void
    {
        $this->level = ImportantLevel::notImportant();
    }

    public function makeImportant(): void
    {
        $this->level = ImportantLevel::important();
    }

    public function makeVeryImportant(): void
    {
        $this->level = ImportantLevel::veryImportant();
    }
}