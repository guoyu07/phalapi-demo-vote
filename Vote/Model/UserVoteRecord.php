<?php

class Model_UserVoteRecord {

    const EXPIRE_ONE_DAY = 86400;

    public function isCanVoteToday($userId) {
        $key = $this->formatKey($userId);

        $dailyVoteTimes = DI()->cache->get($key);

        return $dailyVoteTimes < DI()->config->get('app.max_daily_vote_times') ?  true: false;
    }

    public function addTodayVoteTimes($userId, $times = 1) {
        $key = $this->formatKey($userId);

        $todayTimes = DI()->cache->get($key);
        $todayTimes = intval($todayTimes);

        DI()->cache->set($key, $todayTimes + $times, self::EXPIRE_ONE_DAY);
    }

    protected function formatKey($userId) {
        return 'user_daily_vote_' . $userId . date('Ymd', $_SERVER['REQUEST_TIME']);
    }
}
