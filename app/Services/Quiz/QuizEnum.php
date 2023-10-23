<?php

namespace App\Services\MasterMechanics;

class QuizEnum
{

    // BEGIN:: api -> master-mechanic/video
    const VIDEO_EN = [
        'You need to download the video to watch and learn the video',
        'After downloading the video you have to watch the entire video otherwise you will not be able to participate in the quiz',
        'After watching the complete video you can participate in the quiz game'
    ];
    const VIDEO_BN = [
        'ভিডিওটি দেখতে এবং শিখতে আপনাকে ভিডিওটি ডাউনলোড করতে হবে',
        'ভিডিওটি ডাউনলোড করার পর আপনাকে সম্পূর্ণ ভিডিও দেখতে হবে অন্যথায় আপনি কুইজে অংশ নিতে পারবেন না',
        'সম্পূর্ণ ভিডিও দেখার পর আপনি কুইজ খেলায় অংশগ্রহণ করতে পারবেন'
    ];
    // END:: api -> master-mechanic/video

    // BEGIN:: api -> master-mechanic/enroll
    const ENROLL_EN = [
        'You have to participate in the quiz to win the prize',
        'In each quiz you have to provide specific answers',
        'After successfully completing a quiz you can move to the next level and get some points',
        'You will be rewarded against points earned'
    ];

    const ENROLL_BN = [
        'পুরস্কার জিতার জন্যে আপনাকে কুইজ এ অংশগ্রহণ করতে হবে',
        'প্রতিটি কুইজে আপনাকে নির্দিষ্ট প্রশ্নের উত্তর দিতে হবে',
        'একটি কুইজ সফলভাবে সম্পূর্ণ করার পর আপনি পরবর্তী লেভেলে যেতে পারবেন এবং কিছু পয়েন্ট পাবেন',
        'অর্জিত পয়েন্টের বিপরীতে আপনাকে পুরুস্কিত করা হবে'
    ];
    // END:: api -> master-mechanic/enroll

}
