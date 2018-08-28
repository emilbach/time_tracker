<template>
    <div class="col-md-12">
        <div class="pull-right m-3">
            <date-picker @change="getAllTasks(value)" v-model="value" :lang="lang" :not-after="new Date()"
                         range></date-picker>
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
            <tr v-if="tasks === 0">
                <td class="text-center" colspan="4">No Data For The Chosen Range</td>
            </tr>
            <tr v-else v-for="(task, index) in tasks">
                <td>{{index+1}}</td>
                <td>{{task.task_description}}</td>
                <td>{{task.duration}}</td>
                <td>{{task.created_at}}</td>
                <td>
                    <button class="btn btn-sm btn-info" @click="edit_task(task.id)"><i class="fa fa-pencil"></i></button>
                    <button v-show="false" class="btn btn-sm btn-primary"><i class="fa fa-save"></i></button>
                    <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import DatePicker from 'vue2-datepicker'

    export default {
        components: {DatePicker},
        mounted() {
            this.getAllTasks();
        },
        methods: {
            getAllTasks(value) {
                console.log(value);
                /*axios.get('get_tasks').then(function (response) {
                    this.tasks = response.data;
                }.bind(this)).catch(function (error) {
                    console.log(error.response.data.errors)
                })*/
                axios.post('get_tasks', {
                    dates: (value !== undefined) ? value : null
                }).then(function (response) {
                    this.tasks = response.data;
                }.bind(this)).catch(function (error) {
                    console.log(error.response.data.errors)
                })
            },
            edit_task(id){
                this.edit_index = id;
                console.log(this.edit_index);
            }
        },
        data() {
            return {
                tasks: [],
                value: '',
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
            }
        }
    }
</script>
