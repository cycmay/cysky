var FundingFactory = artifacts.require("./crowdfunding.sol");

module.exports = function(deployer) {
	  deployer.deploy(FundingFactory);
};
