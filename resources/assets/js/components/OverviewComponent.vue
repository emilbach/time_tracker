<template>
    <div class="col-md-12">
        <div class="pull-right m-3">
            <date-picker @change="getAllTasks(date_range)" v-model="date_range" :lang="lang" :not-after="new Date()"
                         range></date-picker>
        </div>
        <div class="pull-right m-3">
            <input class="form-control" type="text" v-model="search" placeholder="Search task description.."/>
        </div>

        <table class="table table-bordered table-hover mt-5">
            <thead>
            <tr>
                <th>#</th>
                <th>Task Description</th>
                <th>Duration</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="tasks.length < 1">
                <td class="text-center" colspan="5"><h3>No Data</h3></td>
            </tr>
            <tr v-else v-for="(task, index) in filteredTasks">
                <td>{{index+1}}</td>
                <td v-if="edit_index === task.id">
                    <input type="text" class="form-control" v-model="task.task_description">
                </td>
                <td v-else>{{task.task_description}}</td>
                <td v-if="edit_index === task.id">
                    <div class="form-inline input-group input-group-sm">
                        <input id="hours" class="form-control text-center"
                               type="number"
                               min="0" v-model="time.hours">
                        <label class="mr-1 ml-1">:</label>
                        <input id="minutes" class="form-control text-center"
                               type="number"
                               min="0" max="59" v-model="time.minutes">
                        <label class="mr-1 ml-1">:</label>
                        <input id="seconds" class="form-control text-center"
                               type="number"
                               min="0" max="59" v-model="time.seconds">
                    </div>
                </td>
                <td v-else>{{task.duration}}</td>
                <td v-if="edit_index === task.id">
                    <date-picker :width="'100%'" v-model="task.created_at" type="datetime" :lang="lang"
                                 :not-after="new Date()"></date-picker>
                </td>
                <td v-else>{{task.created_at}}</td>
                <td class="text-center">
                    <button title="Edit" v-if="edit_index === null" class="btn btn-sm btn-info"
                            @click="edit_task(task.id, index)"><i class="fa fa-pencil"></i>
                    </button>
                    <button title="Save"
                            @click="save_task(task.id, task.task_description,
                            time.hours, time.minutes, time.seconds, task.created_at, index)"
                            v-if="edit_index === task.id" class="btn btn-sm btn-primary"><i
                            class="fa fa-save"></i>
                    </button>
                    <button title="Cancel" v-if="edit_index !== null" class="btn btn-sm btn-info"
                            @click="edit_index = null"><i class="fa fa-times"></i>
                    </button>
                    <button title="Delete" class="btn btn-sm btn-danger" @click="deleteTask(task.id, index)"><i
                            class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<style>
    .mx-input-append {
        height: 30px !important; /* Positioning of the calendar icon in the datepicker component */
    }
</style>
<script>
    import DatePicker from 'vue2-datepicker'

    export default {
        components: {DatePicker},
        mounted() {
            this.getAllTasks();
        },
        computed: {
            filteredTasks() {
                return this.tasks.filter(task => {
                    return task.task_description.toLowerCase().includes(this.search.toLowerCase())
                })
            }
        },
        methods: {
            getAllTasks(value) {
                axios.post('get_tasks', {
                    dates: (value !== undefined) ? value : null
                }).then(function (response) {
                    this.tasks = response.data;
                }.bind(this)).catch(function (error) {
                    console.log(error.response.data.errors)
                })
            },
            deleteTask(id, index) {
                axios.post('delete_task', {
                    id: id
                }).then(function (response) {
                    this.tasks.splice(index, 1);
                }.bind(this)).catch(function (error) {
                    console.log(error)
                })
            },
            save_task(id, desc, hour, min, sec, created, index) {
                let dur = this.beautifyTime(hour, 2) + ':' + this.beautifyTime(min, 2) + ':' + this.beautifyTime(sec, 2);
                this.tasks[index].duration = dur;
                this.tasks[index].created_at = this.beautifyCreatedDate(created);
                axios.post('save_task', {
                    task_description: desc,
                    duration: dur,
                    created_at: created,
                    task_id: id
                }).then(function (response) {
                    this.edit_index = null;

                }.bind(this)).catch(function (error) {
                    console.log(error.response.data.errors);
                })
            },
            edit_task(id, index) {
                this.edit_index = id;
                this.time.hours = this.getDurationElements(this.tasks[index].duration, 0);
                this.time.minutes = this.getDurationElements(this.tasks[index].duration, 1);
                this.time.seconds = this.getDurationElements(this.tasks[index].duration, 2);
            },
            getDurationElements(el, index) {
                let timer = el.split(':');
                return timer[index];
            },
            beautifyTime(num, digit) {
                let zero = '';
                for (let i = 0; i < digit; i++) {
                    zero += '0';
                }
                return (zero + num).slice(-digit);
            },
            beautifyCreatedDate(date) {
                let el = new Date(date);
                return el.getFullYear() + "-" + this.beautifyTime(el.getMonth() + 1, 2) + "-" + this.beautifyTime(el.getDate(), 2) + " " + this.beautifyTime(el.getHours(), 2) + ":" + this.beautifyTime(el.getMinutes(), 2) + ":" + this.beautifyTime(el.getSeconds(), 2);
            }
        },
        data() {
            return {
                tasks: [],
                date_range: '',
                lang: {
                    days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    pickers: ['next 7 days', 'next 30 days', 'previous 7 days', 'previous 30 days'],
                    placeholder: {
                        date: 'Select Date',
                        dateRange: 'Select Date Range'
                    }
                },
                edit_index: null,
                time: {
                    hours: 0,
                    minutes: 0,
                    seconds: 0
                },
                search: '',
            }
        }
    }
</script>
