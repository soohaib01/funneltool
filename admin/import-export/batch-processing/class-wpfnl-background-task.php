<?php

namespace WPFunnels\Batch;

use WP_Background_Process;

abstract class Wpfnl_Background_Task extends WP_Background_Process
{
    protected $current_item;

    protected $provider;



    /**
     * See if the batch limit has been exceeded.
     *
     * @return bool
     */
    protected function batch_limit_exceeded()
    {
        return $this->time_exceeded() || $this->memory_exceeded();
    }

    /**
     * Handle.
     *
     * Pass each queue item to the task handler, while remaining
     * within server memory and time limit constraints.
     */
    protected function handle()
    {
        $this->lock_process();
        do {
            $batch = $this->get_batch();

            foreach ($batch->data as $key => $value) {
                $task = $this->task($value);

                if (false !== $task) {
                    $batch->data[ $key ] = $task;
                } else {
                    unset($batch->data[ $key ]);
                }

                if ($this->batch_limit_exceeded()) {
                    // Batch limits reached.
                    break;
                }
            }

            // Update or delete current batch.
            if (! empty($batch->data)) {
                $this->update($batch->key, $batch->data);
            } else {
                $this->delete($batch->key);
            }
        } while (! $this->batch_limit_exceeded() && ! $this->is_queue_empty());

        $this->unlock_process();

        // Start next batch or complete process.
        if (! $this->is_queue_empty()) {
            $this->dispatch();
        } else {
            $this->complete();
        }
    }


    /**
     * Dispatch updater.
     *
     * Updater will still run via cron job if this fails for any reason.
     */
    public function dispatch()
    {
        $dispatched = parent::dispatch();

        if (is_wp_error($dispatched)) {
            wp_die($dispatched);
        }
    }


    protected function complete()
    {
        $this->on_batch_completed();
        parent::complete(); // TODO: Change the autogenerated stub
    }


    private function on_batch_completed()
    {
    }
}
