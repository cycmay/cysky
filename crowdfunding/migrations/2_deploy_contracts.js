var Adoption = artifacts.require("./crowdfunding.sol");

module.exports = function(deployer) {
	  deployer.deploy(FundingFactory);
};
