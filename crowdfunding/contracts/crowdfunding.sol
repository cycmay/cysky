pragma solidity >=0.4.25;

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
    
    function createFunding(string memory _projectName, uint _supportMoney, uint _goalMoney) public {
        Funding funding = new Funding(_projectName, _supportMoney, _goalMoney, msg.sender, p2f);
        fundings.push(address(funding));
        
        // 把创建者创建的合约地址保存到其数组中
        creatorToFundings[msg.sender].push(address(funding));
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
    
    PlayerToFundings p2f;
    
    // 付款请求的数组
    Request[] public requests; 
    
    struct Request {
        // 描述这笔付款请求是干啥的
        string description;
        // 花多少钱, 钱要少于balance
        uint money;
        // 钱汇给谁. 真正的收钱方
        address payable shopAddress;
        // 代表当前付款请求已经处理完毕
        bool complete;
        // 已投票的用户地址
        mapping(address=>bool) votedMap;
        uint votedCount;
    } 
    
    //构造函数
    constructor(string memory _projectName, uint _supportMoney, uint _goalMoney, address sender, PlayerToFundings _p2f) public{
        manager = sender;
        projectName =_projectName;
        supportMoney = _supportMoney;
        goalMoney = _goalMoney;
        endTime = now + 4 weeks;
        p2f = _p2f;
    }

    // 我要支持(需要付钱)
    function support() public payable{
        require(msg.value == supportMoney);
        // 放进集合中
        players.push(msg.sender);
        playersMap[msg.sender] = true;
        p2f.join(msg.sender, address(this));
    }
    
    // 付款申请函数,由众筹发起人调用
    function createRequest(string memory _description, uint _money, address payable _shopAddress) public onlyManagerCanCall{
        // 余额大于等于付款请求 
        require(address(this).balance >= _money);
        
        Request memory request = Request({
            description: _description,
            money: _money,
            shopAddress: _shopAddress,
            complete: false,
            votedCount: 0
        });
        
        requests.push(request);
    }
    
    // 付款批准函数, 由众筹参与人调用
    function approveRequest(uint index) public {
        // 是否是众筹参与者
        require(playersMap[msg.sender]);
        
        // 防止一个人重复投票
        Request storage request = requests[index];
        require(!request.votedMap[msg.sender]);
        
        request.votedMap[msg.sender] = true;
        request.votedCount++;
    }
    // 众筹发起人调用, 可以调用完成付款
    function finalizeRequest(uint index) public onlyManagerCanCall{
        
        Request storage request = requests[index];
        require(!request.complete);
        // 钱够
        require(address(this).balance >= request.money);
        // 人数过半
        require(request.votedCount * 2 >= getPlayersCount());
        
        // 执行转账操作
        request.shopAddress.transfer(request.money);
        request.complete = true;
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
