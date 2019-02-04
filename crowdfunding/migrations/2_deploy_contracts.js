var Adoption = artifacts.require("FundingFactory");

module.exports = function(deployer) {
	  deployer.deploy(FundingFactory);
};
