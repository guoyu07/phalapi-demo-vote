<?php
class Api_Act extends PhalApi_Api {

    public function getRules() {
        return array(
            'joinIn' => array(
                'teamName' => array('name' => 'team_name', 'require' => true, 'min' => 1, 'max' => 100),
            ),
            'vote' => array(
                'teamId' => array('name' => 'team_id', 'require' => true, 'type' => 'int', 'min' => 1),
            ),
        );
    }

    public function joinIn() {
        $rs = array('code' => 0, 'team_id' => 0);

        DI()->userLite->check(true);

        $domain = new Domain_Team();
        if ($domain->isExists($this->teamName)) {
            $rs['code'] = 1;
            return $rs;
        }

        $teamId = $domain->joinIn($this->teamName);
        $rs['team_id'] = $teamId;

        return $rs;
    }

    public function showList() {
        $rs = array('code' => 0, 'teams' => array());

        DI()->userLite->check(true);

        $domain = new Domain_Team();
        $teams = $domain->showList();

        $rs['teams'] = $teams;

        return $rs;
    }

    public function vote() {
        $rs = array('code' => 0, 'vote_num' => 0);

        DI()->userLite->check(true);

        $domain = new Domain_Team();
        if (!$domain->isJoinIn($this->teamId)) {
            $rs['code'] = 1;
            return $rs;
        }

        $domainVote = new Domain_Vote();
        if (!$domainVote->isCanVoteToday($this->userId)) {
            DI()->logger->debug('user can not vote today', 
                array('userId' => $this->userId, 'teamId' => $this->teamId));

            $rs['code'] = 2;
            return $rs;
        }

        $voteNum = $domainVote->vote($this->userId, $this->teamId);
        $rs['vote_num'] = $voteNum;

        return $rs;
    }
}
