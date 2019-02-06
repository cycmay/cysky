<script src="/assets/js/web3.min.js"></script>
<script src="/assets/js/truffle-contract.js"></script>
<script type="text/javascript" src="/assets/js/jquery.js"></script>

<script>

// messages
App = {
  web3Provider: null,
  contracts: {},
  init: function() {
    return App.initWeb3();
  },

  initWeb3: function() {
    // Initialize web3 and set the provider to the testRPC.
    if (typeof web3 !== 'undefined') {
      App.web3Provider = web3.currentProvider;
      web3 = new Web3(web3.currentProvider);
    } else {
      // set the provider you want from Web3.providers
      App.web3Provider = new Web3.providers.HttpProvider('http://192.168.31.35:8545');
      web3 = new Web3(App.web3Provider);
    }
    console.log("initWeb3 completed!");
    return App.initContract();
  },

  initContract: function() {
    $.getJSON('/assets/build/contracts/FundingFactory.json', function(data) {
      // Get the necessary contract artifact file and instantiate it with truffle-contract.
      var FundingFactoryArtifact = data;
      //console.log(FundingFactoryArtifact);
      App.contracts.FundingFactory = TruffleContract(FundingFactoryArtifact);
      // Set the provider for our contract.
      App.contracts.FundingFactory.setProvider(App.web3Provider);
    });
    $.getJSON('/assets/build/contracts/Funding.json', function(data) {
      var FundingArtifact = data;
      App.contracts.Funding = TruffleContract(FundingArtifact);
      App.contracts.Funding.setProvider(App.web3Provider);
    });
    $.getJSON('/assets/build/contracts/PlayerToFundings.json', function(data) {
      var PlayerToFundingsArtifact = data;
      App.contracts.PlayerToFundings = TruffleContract(PlayerToFundingsArtifact);
      App.contracts.PlayerToFundings.setProvider(App.web3Provider);
    });
    console.log("initContract completed!");
    //return App.bindEvents();
    //return App.handleTransfer();
  },

  sleep: function(n) {
    var start=new Date().getTime();
    while(true) if(new Date().getTime()-start>n) break;
  },

  bindEvents: function() {
    return App.handleTransfer();
  },

  thransferEasy: function(amount, contractAddress, my_post) {
    var FundingInstance;

    web3.eth.getAccounts(function(error, accounts) {
      if(error){
      	console.error(error);
      }
      else{
      	var account = accounts[0]; 
      	//console.log(typeof(contractAddress));
      	App.contracts.Funding.at(contractAddress).then(function(instance){
        	FundingInstance = instance;
        	return FundingInstance.support({from: account, value: window.web3.toWei(amount, "ether")});
        }).then(function (result) {
		      // 处理查询结果
		      console.log(result);
          $.ajax(my_post);
		    }).catch(function (e) {
		      console.error(e); // 打印warning
		    });
      }	
    });
  },
  creatFunding: function(projectId, goalmoney, my_post){
  	App.contracts.FundingFactory.deployed().then(function(instance){
  		return instance.createFunding(projectId, window.web3.toWei(goalmoney));
  	}).then(function (result) {
  		// 处理查询结果
  		console.log(result);
  		var temp;
  		App.contracts.FundingFactory.deployed().then(function(instance){
  			return instance.getFundings.call();
  		}).then(function(result){
  			console.log(result);
  			console.log(result[result.length-1]);
  			my_post["success"] = function(url){
						console.log(url);
						window.location.replace($.trim(url));}
			my_post["data"] = my_post["data"] + "&contract_address="+result[result.length-1];
			console.log(my_post);
  			$.ajax(my_post);
  		})
  	});
	/*var FundingInstance;
	App.contracts.Funding.new({inputs:[projectId, goalmoney]}).then(function(instance){
		FundingInstance = instance;
		return FundingInstance;
	}).then(function(result){
		console.log(result);
		console.log(result.address);
	}).catch(function(e){
		console.error(e);
	})*/
  },
  getFundings: function(){
  	App.contracts.FundingFactory.deployed().then(function(instance){
  		return instance.getFundings.call();
  	}).then(function (result) {
		      // 处理查询结果
		      console.log(result);
		    }).catch(function (e) {
		      console.error(e); // 打印warning
		    });
  }
};

$(function() {
  $(window).load(function() {
    App.init();
  });
});

</script>