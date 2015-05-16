<?php

class Domain_Vote {

    public function isCanVoteToday($userId) {
        $model = new Model_UserVoteRecord();
        return $model->isCanVoteToday($userId);
    } 

    public function vote($userId, $teamId) {
        $model = new Model_Vote();
        $voteNum = $model->vote($teamId);

        $modelRecord = new Model_UserVoteRecord();
        $modelRecord->addTodayVoteTimes($userId);

        return $voteNum;
    }
}
