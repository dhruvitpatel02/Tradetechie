<?php

return [
    [
        'title' => 'What is Stock Market',
        'category' => 'basics',
        'content' => 'The stock market is a place where shares of companies are bought and sold. When you buy a share, you become a small owner of that company. Companies use the stock market to raise money for growth, and investors use it to grow their wealth.',
        'example' => 'If you buy 1 share of TCS, you own a small part of TCS.',
        'risk_note' => 'Stock prices go up and down, so returns are not guaranteed.',
        'order_position' => 1
    ],
    [
        'title' => 'NSE and BSE',
        'category' => 'basics',
        'content' => 'NSE (National Stock Exchange) and BSE (Bombay Stock Exchange) are India\'s two main stock exchanges. NSE is known for higher trading volume, while BSE is Asia\'s oldest stock exchange.',
        'example' => 'Nifty 50 belongs to NSE, Sensex belongs to BSE.',
        'risk_note' => 'Both exchanges are regulated, but market risk still exists.',
        'order_position' => 2
    ],
    [
        'title' => 'What is Equity',
        'category' => 'basics',
        'content' => 'Equity means ownership in a company. When you buy equity shares, you become a shareholder and can benefit from price appreciation and dividends.',
        'example' => 'Buying Infosys shares for long-term investment.',
        'risk_note' => 'Equity value depends on company performance and market conditions.',
        'order_position' => 3
    ],
    [
        'title' => 'What is Short Selling',
        'category' => 'technical',
        'content' => 'Short selling is a trading method where you sell a stock first and buy it later at a lower price. It is mainly used in intraday trading. How it works: You sell a stock you don\'t own, price falls, you buy it back cheaper. Profit = selling price – buying price.',
        'example' => 'Sell a stock at ₹500, buy back at ₹450 → profit ₹50.',
        'risk_note' => 'Loss can be unlimited if the price goes up.',
        'order_position' => 4
    ],
    [
        'title' => 'Commodity Trading',
        'category' => 'advanced',
        'content' => 'Commodity trading involves buying and selling physical goods like gold, silver, crude oil, and agricultural products. In India, commodities are traded mainly on MCX.',
        'example' => 'Gold futures trading on MCX.',
        'risk_note' => 'Commodity prices are affected by global events and currency changes.',
        'order_position' => 5
    ],
    [
        'title' => 'What are Options',
        'category' => 'advanced',
        'content' => 'Options are financial contracts that give the buyer the right, but not the obligation, to buy or sell an asset at a fixed price before expiry. Key terms: Strike Price, Premium, Expiry Date.',
        'example' => 'Buying options to speculate or hedge positions.',
        'risk_note' => 'Options are risky and require proper understanding.',
        'order_position' => 6
    ],
    [
        'title' => 'Call Option',
        'category' => 'advanced',
        'content' => 'A call option gives the buyer the right to buy a stock at a fixed price. Traders buy call options when they expect the market to go up.',
        'example' => 'Buying a call option of Nifty when expecting an uptrend.',
        'risk_note' => 'Option premium can become zero if prediction is wrong.',
        'order_position' => 7
    ],
    [
        'title' => 'Put Option',
        'category' => 'advanced',
        'content' => 'A put option gives the buyer the right to sell a stock at a fixed price. Traders buy put options when they expect the market to fall.',
        'example' => 'Buying a put option to protect a portfolio.',
        'risk_note' => 'Wrong market direction leads to premium loss.',
        'order_position' => 8
    ],
    [
        'title' => 'Option Buying vs Option Selling',
        'category' => 'advanced',
        'content' => 'Option buyers have limited loss but limited time. Option sellers earn premium but face higher risk. Comparison: Buyer has limited loss and high reward. Seller has limited reward and high risk.',
        'example' => 'Buying options for speculation, selling options for income.',
        'risk_note' => 'Option selling requires experience and margin.',
        'order_position' => 9
    ],
    [
        'title' => 'Fundamental Analysis',
        'category' => 'fundamental',
        'content' => 'Fundamental analysis studies a company\'s financial health using revenue, profit, balance sheet, and ratios like PE and ROE.',
        'example' => 'Analyzing TCS financials before investing.',
        'risk_note' => 'Good fundamentals don\'t guarantee short-term gains.',
        'order_position' => 10
    ],
    [
        'title' => 'Technical Analysis',
        'category' => 'technical',
        'content' => 'Technical analysis studies price charts and indicators to predict future price movement. Key tools: Support & Resistance, Trendlines, Volume.',
        'example' => 'Using RSI and MACD indicators for trading decisions.',
        'risk_note' => 'Indicators can give false signals.',
        'order_position' => 11
    ],
    [
        'title' => 'Risk Management in Trading',
        'category' => 'advanced',
        'content' => 'Risk management helps traders protect capital. It includes stop-loss, position sizing, and capital allocation.',
        'example' => 'Risking only 1–2% of capital per trade.',
        'risk_note' => 'Ignoring risk management leads to losses.',
        'order_position' => 12
    ],
    [
        'title' => 'Trading Psychology',
        'category' => 'advanced',
        'content' => 'Trading psychology focuses on emotions like fear and greed. Successful traders follow discipline and rules.',
        'example' => 'Not overtrading after a loss.',
        'risk_note' => 'Emotional trading is one of the biggest reasons for failure.',
        'order_position' => 13
    ]
];
