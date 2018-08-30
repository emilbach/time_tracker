<template>

    <div class="col-md-12">
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="col-md-12">

                    <label for="task_desc">Task Description</label>
                    <div class="btn-toolbar pull-right" role="toolbar">
                        <div class="btn-group btn-group-sm mr-1" role="group">
                            <button class="btn btn-success" data-toggle="modal" data-target="#myModal">
                                Enter custom timer duration
                            </button>
                        </div>
                        <div class="btn-group btn-group-sm" role="group">
                            <button v-if="task_description.length > 0 && validSave" @click="saveTask"
                                    class="btn btn-success">Save
                            </button>
                            <button v-else disabled class="btn btn-success">Save</button>
                        </div>
                    </div>


                    <textarea v-model="task_description" style="width: 100%" name="task_description" id="task_desc"
                              cols="30" rows="10"></textarea>

                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12 text-center">

                    <h3 class="text-center">{{ result }}</h3>

                    <button v-if="!play_pause_flag" class="btn btn-sm btn-primary" @click="startTimer"><i
                            class="fa fa-play-circle fa-2x" aria-hidden="true"></i>
                    </button>
                    <button v-else-if="play_pause_flag" class="btn btn-sm btn-primary" @click="pauseTimer"><i
                            class="fa fa-pause-circle fa-2x"
                            aria-hidden="true"></i>
                    </button>
                    <button v-if="stop_flag" class="btn btn-sm btn-primary" @click="stopTimer"><i
                            class="fa fa-stop-circle fa-2x"
                            aria-hidden="true"></i>
                    </button>


                </div>


            </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">Timer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <label for="hours">Hours</label>
                                <input id="hours" class="form-control text-center" v-model="time.hours" type="number"
                                       min="0">
                            </div>

                            <div class="col-md-4 text-center">
                                <label for="minutes">Minutes</label>
                                <input id="minutes" class="form-control text-center" v-model="time.minutes"
                                       type="number"
                                       min="0" max="59">
                            </div>

                            <div class="col-md-4 text-center">
                                <label for="seconds">Seconds</label>
                                <input id="seconds" class="form-control text-center" v-model="time.seconds"
                                       type="number"
                                       min="0" max="59">
                            </div>

                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h4>Date:</h4>
                                <date-picker v-model="date_created" type="datetime" :lang="lang"
                                             :not-after="new Date()"></date-picker>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="updateTime">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import DatePicker from 'vue2-datepicker'

    export default {
        components: {DatePicker},
        mounted() {
        },
        computed: {
            validSave() {
                return this.time.seconds !== 0 || this.time.minutes !== 0 || this.time.hours !== 0;
            }
        },
        methods: {
            /**Configuring the basics of the timer and making the timer to be in a user friendly format hh:mm:ss*/
            updateTime() {
                if (this.timerID != null) {
                    this.time.seconds++;
                }
                if (this.time.seconds > 59) {
                    this.time.minutes++;
                    this.time.seconds = 0;
                }
                if (this.time.minutes > 59) {
                    this.time.hours++;
                    this.time.minutes = 0;
                }
                this.result = this.beautifyTime(this.time.hours, 2) + ':' + this.beautifyTime(this.time.minutes, 2) + ':' + this.beautifyTime(this.time.seconds, 2);
                $('#myModal').modal('hide')
            },
            /**Setting an interval to call the updateTime every second.
             * The play_pause_flag and stop_flag are for allowing the necessary buttons to be pressed*/
            startTimer() {
                this.timerID = setInterval(this.updateTime, 1000);
                this.play_pause_flag = true;
                this.stop_flag = true;
            },
            pauseTimer() {
                clearInterval(this.timerID);
                this.play_pause_flag = false;
            },
            stopTimer() {
                clearInterval(this.timerID);
                this.time.hours = this.time.minutes = this.time.seconds = 0;
                this.result = "00:00:00";
                this.play_pause_flag = false;
                this.stop_flag = false;
                this.timerID = null;
            },
            /**If the hours/minutes/seconds are one digit numbers add a 0 in front*/
            beautifyTime(num, digit) {
                let zero = '';
                for (let i = 0; i < digit; i++) {
                    zero += '0';
                }
                return (zero + num).slice(-digit);
            },
            /**Saving the task and resetting the timer.*/
            saveTask() {
                axios.post('save_task', {
                    task_description: this.task_description,
                    duration: this.result,
                    created_at: (this.date_created !== '') ? this.date_created : false
                }).then(function (response) {
                    alert(response.data);
                    this.stopTimer();
                    this.task_description = "";

                }.bind(this)).catch(function (error) {
                    console.log(error.response.data.errors);

                })
            }
        },
        data() {
            return {
                time: {
                    hours: 0,
                    minutes: 0,
                    seconds: 0
                },
                result: '00:00:00',
                timerID: null,
                play_pause_flag: false,
                stop_flag: false,
                task_description: "",
                date_created: '',
                lang: {
                    days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    placeholder: {
                        date: 'Select Date',
                        dateRange: 'Select Date Range'
                    }
                }
            }
        }
    }
</script>
