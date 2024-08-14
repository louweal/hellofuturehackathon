// SPDX-License-Identifier: MIT
pragma solidity ^0.8.2;

contract ReviewContract {
    enum State { Pending, Reviewed }
    
    // State variables
    // transactionId = customer@bSeconds.bNanoseconds
    address payable public customer; 
    address payable public shopOwner;
    uint256 public bSeconds; // buy timestamp in seconds
    uint256 public bNanoseconds; // buy timestamp nanoseconds
    uint64 public cashback;
    uint32 public rSeconds; // review timestamp in seconds, range valid until 7 February 2106 06:28:15
    uint32 public iSeconds; // minimum number of seconds (interval) between buy and review
    string public cid;
    State public state;

    // Payable constructor
    constructor(uint32 _bSeconds, uint256 _bNanoseconds, uint64 _amount, uint64 _cashback, address _shopOwner, uint32 _iSeconds ) payable {
        require(msg.value == _amount, "Incorrect value send"); 
        customer = payable(msg.sender);
        shopOwner = payable(_shopOwner);
        bSeconds = _bSeconds;
        bNanoseconds = _bNanoseconds;
        cashback = _cashback;
        iSeconds = _iSeconds;
        state = State.Pending; 
        shopOwner.transfer(_amount - cashback);
    }

    // Payable function to attach a review
    function AttachReview(string memory _cid) external inState(State.Pending) onlyCustomer {
        require(block.timestamp >= (bSeconds + iSeconds), "Review attempt is too soon");
        cid = _cid;
        rSeconds = uint32(block.timestamp); // block timestamp is in seconds
        state = State.Reviewed;
        customer.transfer(cashback); // customer gets his cashback
    }

        // modifiers

    /// The function cannot be called at the current state.
    error InvalidState();

    modifier inState(State state_) {
        if (state != state_) {
            revert InvalidState();
        }
        _;
    }

    /// Only the customer can call this function
    error OnlyCustomer();

    modifier onlyCustomer() {
        if (msg.sender != customer) {
            revert OnlyCustomer();
        }
        _;
    }
}