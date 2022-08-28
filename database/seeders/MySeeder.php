<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class MySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fronts')->insert([
            'type' => 'essay','level' => 'college','format' => 'APA','unitprice' =>20,'writepercentage' => 100,'editpercentage' => 50,]);
        DB::table('fronts')->insert([
            'type' => 'QA','level' => 'university','format' => 'Havard','unitprice' =>10,'writepercentage' => 100,'editpercentage' => 0,]);

        //add users
        DB::table('users')->insert([
            'userid' => 'writer01','role' => 4, 'name' =>'Silomalo Joseph','email' => 'silomalojoseph@gmail.com','password' =>'notneeded']);
        DB::table('users')->insert([
            'userid' => 'student','role' => 3,'inbox' => 0,'outbox' => 0,'email' => 'student@gmail.com','password' =>Hash::make('student@gmail.com')]);
        DB::table('users')->insert([
            'userid' => 'student1','role' => 3,'inbox' => 0,'outbox' => 0,'email' => 'student1@gmail.com','password' =>Hash::make('student1@gmail.com')]);
        DB::table('users')->insert([
            'userid' => 'student2','role' => 3,'inbox' => 0,'outbox' => 0,'email' => 'student2@gmail.com','password' =>Hash::make('student2@gmail.com')]);
        DB::table('users')->insert([
            'userid' => 'student3','role' => 3,'inbox' => 0,'outbox' => 0,'email' => 'student3@gmail.com','password' =>Hash::make('student3@gmail.com')]);
        DB::table('users')->insert([
            'userid' => 'support','role' => 2,'inbox' => 0,'outbox' => 0, 'name' =>'Supoort name','email' => 'support@gmail.com','password' =>Hash::make('support@gmail.com')]);
        DB::table('users')->insert([
            'userid' => 'support1','role' => 2,'inbox' => 0,'outbox' => 0, 'name' =>'Supoort1 name','email' => 'support1@gmail.com','password' =>Hash::make('support1@gmail.com')]);
        DB::table('users')->insert([
            'userid' => 'admin','role' => 1,'inbox' => 0,'outbox' => 0,'email' => 'admin@gmail.com','password' =>Hash::make('admin@gmail.com')]);

        //to types
        DB::table('types')->insert(['title' => 'essay']);
        DB::table('types')->insert(['title' => 'report']);
        DB::table('types')->insert(['title' => 'QA']);
        DB::table('types')->insert(['title' => 'journals']);
        //to levels
        DB::table('levels')->insert(['title' => 'university']);
        DB::table('levels')->insert(['title' => 'college']);
        DB::table('levels')->insert(['title' => 'highschool']);
        DB::table('levels')->insert(['title' => 'masters']);
        //to format
        DB::table('formats')->insert(['title' => 'APA']);
        DB::table('formats')->insert(['title' => 'Chilago']);
        DB::table('formats')->insert(['title' => 'MLA']);
        DB::table('formats')->insert(['title' => 'Havard']);
        //money
        DB::table('money')->insert(['payment_id' => 'payid1' ,'taskid' => 'task001','payment_method'=> 'paypal','user_id' => 'student','status' => 'InComplete','amount' => 23]);
        DB::table('money')->insert(['payment_id' => 'payid2' ,'taskid' => 'taskoo2','payment_method'=> 'paypal','user_id' => 'student','status' => 'complete','amount' => 45]);
        DB::table('money')->insert(['payment_id' => 'payid3' ,'taskid' => 'task993','payment_method'=> 'paypal','user_id' => 'student','status' => 'Incomplete','amount' => 45]);
        DB::table('money')->insert(['payment_id' => 'payid4' ,'taskid' => 'task004','payment_method'=> 'paypal','user_id' => 'student','status' => 'complete','amount' => 45]);
        //tasks
        DB::table('tasks')->insert(['userid' => 'student' ,'taskid' => 'task001','duration' => '1212/12/12','title' => 'Title1','mode' => 'write','level' => 'College','type' => 'essay','page' => 3,'format' => 'APA','state' => 'new','price' => 23]);
        DB::table('tasks')->insert(['userid' => 'student' ,'taskid' => 'task001','duration' => '1212/12/12','title' => 'Title1','mode' => 'write','level' => 'College','type' => 'essay','page' => 3,'format' => 'APA','state' => 'done','price' => 23]);
        DB::table('tasks')->insert(['userid' => 'student' ,'taskid' => 'task001','duration' => '1212/12/12','title' => 'Title1','mode' => 'write','level' => 'College','type' => 'essay','page' => 3,'format' => 'APA','state' => 'pending','price' => 23]);
    }
}
