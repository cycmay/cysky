pragma solidity >=0.5.0;

contract PlayerToFundings{
    // 参与者=> 合约地址数组 
    mapping(address => address[]) playerToFundings;
    
    function join(address player, address fundingAddress) public {
        playerToFundings[player].push(fundingAddress);
    }
    
    function getPlayerFundings(address player) public view returns(address[] memory){
        return playerToFundings[player];
    }
    
}

contract FundingFactory {
    
    //存储所有已经部署的智能合约的地址 
    address[] public fundings;
    
    // 创建者=> 合约地址数组 
    mapping(address => address[]) creatorToFundings;
    
    PlayerToFundings p2f;
    constructor() public{
        PlayerToFundings p2fAddress = new PlayerToFundings();
        p2f = PlayerToFundings(p2fAddress);
    }
    
    function createFunding(string memory _projectName, uint _goalMoney) public returns(address){
        Funding funding = new Funding(_projectName, _goalMoney, msg.sender, p2f);
        fundings.push(address(funding));
        
        // 把创建者创建的合约地址保存到其数组中
        creatorToFundings[msg.sender].push(address(funding));
        return address(funding);
    }
    
    // 路人 查看所有众筹项目列表
    function getFundings() public view returns(address[] memory){
        return fundings;
    }
    
    // 创建者 查看调用者创建的所有众筹项目地址
    function getCreatorFundings() public view returns(address[] memory){
        return creatorToFundings[msg.sender];
    }
    
    // 参与者 
    function getPlayerFundings() public view returns(address[] memory){
        return p2f.getPlayerFundings(msg.sender);
    }
}

contract Funding {
    
    // 众筹成功标记
    bool public flag = false;
    // 众筹发起人地址(众筹发起人)
    address public manager;
    // 项目名称
    string public projectName;
    // 众筹参与人需要付的钱
    uint public supportMoney;
    // 目标募集的资金(endTime后,达不到目标则众筹失败)
    uint public goalMoney;
    // 默认众筹结束的时间,为众筹发起后的一个月
    uint public endTime;
    // 众筹参与人的数组
    address[] public players;
    mapping(address=>bool) playersMap;
    //众筹参与人amount记录
    mapping(address=>uint) playersRecord;
    
    PlayerToFundings p2f;
    
    // 已投票的用户地址
    mapping(address=>bool) votedMap;
    uint votedCount = 0;
    
    //构造函数
    constructor(string memory _projectName, uint _goalMoney, address sender, PlayerToFundings _p2f) public{
        manager = sender;
        projectName =_projectName;
        goalMoney = _goalMoney;
        endTime = now + 4 weeks;
        p2f = _p2f;
    }

    // 我要支持(需要付钱)
    function support() public payable { 
        //require(msg.value == supportMoney);
        // 检查是否符合要求
        // 防止一个人重复此操作
        require(!votedMap[msg.sender]);
        //标记已经付款
        votedMap[msg.sender] = true;
        votedCount++;
        // 放进集合中
        players.push(msg.sender);
        playersMap[msg.sender] = true;
        playersRecord[msg.sender] = msg.value;
        p2f.join(msg.sender, address(this));
    }
    
    // 所有参与者
    function getPlayers() public view returns(address[] memory){
        return players;
    }
    // 所有参与者个数
    function getPlayersCount() public view returns(uint){
        return players.length;
    }
    // 获取合约的余额
    function getTotalBalance() public view returns(uint){
        return address(this).balance;
    }
    // 获取剩余天数
    function getRemainDays() public view returns(uint) {
        return (endTime - now) / 60 / 60 / 24;
    }
    // 检查众筹状态
    function checkStatus() public onlyManagerCanCall {
        require(!flag);
        
        // 到期
        require(now >= endTime);
        // getTotalBalance金额>= goalMoney
        require(address(this).balance >= goalMoney);
        
        flag = true;
    }
    
    modifier onlyManagerCanCall {
        require(msg.sender == manager);
        _;
    }
}
