<?php


namespace App\API\EntityGetter;


use App\Constant\TablesName;
use Exception;
use Illuminate\Database\Query\Builder;

class Thing extends BaseEntity
{
    public const TABLE_NAME = TablesName::THING;
    public const JOIN_NAME = 't';
    public const JOIN_GET =
    [
        self::JOIN_NAME . '.id',
        self::JOIN_NAME . '.name',
        self::JOIN_NAME . '.description',
        self::JOIN_NAME . '.properties',
    ];
    public const PROPERTIES = [
        'id',
        'name',
        'description',
        'properties',
    ];
    public const PATH_VARIABLE_NAME = 'things';
    //tasking
    public static function toTaskingCap(Builder $builder = null): Builder
    {
        if ($builder == null) {
            $builder = self::selfBuilder();
        }
        return static::joinTable(
            $builder,
            static::JOIN_NAME,
            TaskingCapabilities::TABLE_NAME,
            TaskingCapabilities::JOIN_NAME,
            'id',
            'thing_Id'
        );
    }

    static function toActuator(Builder $builder = null): Builder
    {
        if ($builder == null) {
            // $builder = self::selfBuilder(); v2.0
            $builder = static::toTaskingCap();
        }
        // static::joinTable(
        //     $builder,
        //     static::JOIN_NAME,
        //     TaskingCapabilities::TABLE_NAME,
        //     TaskingCapabilities::JOIN_NAME,
        //     'id',
        //     'thing_Id'
        // ); v2.0
        return TaskingCapabilities::toActuator($builder);
    }

    static function toTask(Builder $builder = null): Builder
    {
        if ($builder == null) {
            // $builder = self::selfBuilder();
            $builder = static::toTaskingCap();
        }
        // static::joinTable(
        //     $builder,
        //     static::JOIN_NAME,
        //     TaskingCapabilities::TABLE_NAME,
        //     TaskingCapabilities::JOIN_NAME,
        //     'id',
        //     'thing_Id'
        // ); v2.0
        return TaskingCapabilities::toTask($builder);
    }

    //sensing
    public static function toDataStream(Builder $builder = null): Builder
    {
        if ($builder == null) {
            $builder = self::selfBuilder();
        }
        static::joinTable(
            $builder,
            static::JOIN_NAME,
            MultiDataStream::TABLE_NAME,
            MultiDataStream::JOIN_NAME,
            'id',
            'thingId'
        );
        //        static::joinTable($builder,'dst',MultiDataStream::TABLE_NAME,MultiDataStream::JOIN_NAME,'dataStreamId','id');
        return MultiDataStream::refObservationType($builder);
    }
    public static function toObservation(Builder $builder = null): Builder
    {
        if ($builder == null) {
            $builder = static::toDataStream();
        }
        return MultiDataStream::toObservation($builder);
    }
    public static function toMeasurementUnit(Builder $builder = null): Builder
    {
        if ($builder == null) {
            $builder = static::toDataStream();
        }
        return MultiDataStream::toMeasurementUnit($builder);
    }
    public static function toObservationDataType(Builder $builder = null): Builder
    {
        if ($builder == null) {
            $builder = static::toDataStream();
        }
        return MultiDataStream::toObservationDataType($builder);
    }
    public static function toSensor(Builder $builder = null): Builder
    {
        if ($builder == null) {
            $builder = self::selfBuilder();
        }
        static::joinTable(
            $builder,
            static::JOIN_NAME,
            MultiDataStream::TABLE_NAME,
            MultiDataStream::JOIN_NAME,
            'id',
            'thingId'
        );
        return MultiDataStream::toSensor($builder);
    }
    public static function toObservedProperty(?Builder $builder): Builder
    {
        if ($builder == null) {
            $builder = static::toDataStream();
        }
        MultiDataStream::toObservedProperty($builder);
        return $builder;
    }

    public static function toLocation(Builder $builder = null): Builder
    {
        if ($builder == null) {
            $builder = self::selfBuilder();
        }
        return static::joinTable(
            $builder,
            static::JOIN_NAME,
            Location::TABLE_NAME,
            Location::JOIN_NAME,
            'id_location',
            'id'
        );
    }

    public static function joinTo(string $pathVariableItem, Builder $builder = null): Builder
    {
        switch ($pathVariableItem) {
            case MultiDataStream::PATH_VARIABLE_NAME:
                $builder = static::toDataStream($builder);
                break;
            case Observation::PATH_VARIABLE_NAME:
                $builder = static::toObservation($builder);
                break;
            case MeasurementUnit::PATH_VARIABLE_NAME:
                $builder = static::toMeasurementUnit($builder);
                break;
            case ObservationType::PATH_VARIABLE_NAME:
                $builder = static::toObservationDataType($builder);
                break;
            case Sensor::PATH_VARIABLE_NAME:
                $builder = static::toSensor($builder);
                break;
            case ObservedProperty::PATH_VARIABLE_NAME:
                $builder = static::toObservedProperty($builder);
                break;
            case Location::PATH_VARIABLE_NAME:
                $builder = static::toLocation($builder);
                break;
                //Tasking 
            case TaskingCapabilities::PATH_VARIABLE_NAME:
                $builder = static::toTaskingCap($builder);
                break;
            case Task::PATH_VARIABLE_NAME:
                $builder = static::toTask($builder);
                break;
            case Actuator::PATH_VARIABLE_NAME:
                $builder = static::toActuator($builder);
                break;
        }
        return $builder;
    }

    /**
     * @throws Exception
     */


    static function toThing(Builder $builder = null): Builder
    {
        throw new Exception("cannot navigate " . static::PATH_VARIABLE_NAME . " to itself");
    }
}
