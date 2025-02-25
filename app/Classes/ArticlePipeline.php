<?php

namespace App\Classes;

/**
 * Class ArticlePipeline
 *
 * A class that represents a pipeline for processing articles through a series
 * of defined steps. Each step can modify the data and the pipeline processes
 * them in sequence.
 */
class ArticlePipeline
{
    /**
     * @var PipelineStep[] An array of steps to be executed in the pipeline.
     */
    private $steps = [];

    /**
     * Adds a step to the pipeline.
     *
     * @param PipelineStep $step An instance of PipelineStep to be added.
     * @return $this The current instance of ArticlePipeline for method chaining.
     */
    public function addStep(PipelineStep $step)
    {
        $this->steps[] = $step;
        return $this;
    }

    /**
     * Processes the data through all the steps in the pipeline.
     *
     * The method iterates over each step, executing it with the current data.
     * If any step returns null, the processing will stop.
     *
     * @param array $data The initial data to be processed by the pipeline.
     * @return array|null The processed data after passing through the pipeline,
     *                    or null if processing was stopped by a step.
     */
    public function process($data = [])
    {
        foreach ($this->steps as $step) {
            $data = $step->execute($data);
            if ($data === null) {
                break; // Stop processing if any step returns null
            }
        }
        return $data;
    }
}
