<?php

namespace App\Services;

class MockDataService
{
    public static function wallet(): array
    {
        return [
            'address'        => 'ST1PQHQKVORUXZFY1DGX8MNSNYVE3VGZUSRTPGZGM',
            'bnsName'        => 'vilansh.btc',
            'reputationScore'=> 780,
            'reputationTier' => 'A',
            'borrowLimit'    => '2.5 sBTC',
            'walletAge'      => 847,
            'loansRepaid'    => 4,
            'defaults'       => 0,
            'transactionCount' => 1247,
            'defiProtocols'  => 12,
        ];
    }

    public static function loanRequests(): array
    {
        return [
            ['id'=>1,'borrower'=>'alex.btc','address'=>'ST1PQ...4ABC','tier'=>'A','amount'=>0.5,'rate'=>7.5,'duration'=>30,'postedAt'=>'2 hours ago','score'=>820,'walletAge'=>923,'loansRepaid'=>6,'defaults'=>0],
            ['id'=>2,'borrower'=>'defi-maven.btc','address'=>'ST2XY...7DEF','tier'=>'A','amount'=>1.2,'rate'=>8.0,'duration'=>60,'postedAt'=>'4 hours ago','score'=>795,'walletAge'=>712,'loansRepaid'=>5,'defaults'=>0],
            ['id'=>3,'borrower'=>'stacksbuilder.btc','address'=>'ST3AB...2GHI','tier'=>'B','amount'=>0.3,'rate'=>12.0,'duration'=>14,'postedAt'=>'6 hours ago','score'=>640,'walletAge'=>456,'loansRepaid'=>3,'defaults'=>0],
            ['id'=>4,'borrower'=>'bitcoin-og.btc','address'=>'ST4CD...9JKL','tier'=>'A','amount'=>2.0,'rate'=>6.5,'duration'=>90,'postedAt'=>'8 hours ago','score'=>890,'walletAge'=>1200,'loansRepaid'=>9,'defaults'=>0],
            ['id'=>5,'borrower'=>'nft-collector.btc','address'=>'ST5EF...3MNO','tier'=>'B','amount'=>0.8,'rate'=>11.0,'duration'=>30,'postedAt'=>'12 hours ago','score'=>660,'walletAge'=>380,'loansRepaid'=>2,'defaults'=>0],
            ['id'=>6,'borrower'=>'yield-farmer.btc','address'=>'ST6GH...6PQR','tier'=>'C','amount'=>0.2,'rate'=>18.0,'duration'=>14,'postedAt'=>'1 day ago','score'=>490,'walletAge'=>180,'loansRepaid'=>1,'defaults'=>1],
            ['id'=>7,'borrower'=>'ordinals.btc','address'=>'ST7IJ...1STU','tier'=>'B','amount'=>0.6,'rate'=>10.5,'duration'=>45,'postedAt'=>'2 days ago','score'=>710,'walletAge'=>560,'loansRepaid'=>4,'defaults'=>0],
            ['id'=>8,'borrower'=>'stacks-dev.btc','address'=>'ST8KL...4VWX','tier'=>'A','amount'=>1.5,'rate'=>7.0,'duration'=>60,'postedAt'=>'3 days ago','score'=>840,'walletAge'=>990,'loansRepaid'=>7,'defaults'=>0],
        ];
    }

    public static function activeLoans(): array
    {
        return [
            ['type'=>'Reputation Loan','amount'=>'0.5000 sBTC','rate'=>8,'daysLeft'=>28,'status'=>'ACTIVE','statusClass'=>'success'],
            ['type'=>'NFT Loan','amount'=>'0.3000 sBTC','rate'=>10,'daysLeft'=>5,'status'=>'WARNING','statusClass'=>'warning'],
        ];
    }

    public static function fundedLoans(): array
    {
        return [
            ['borrower'=>'alex.btc','amount'=>'0.5000 sBTC','rate'=>7.5,'daysLeft'=>22,'status'=>'ACTIVE'],
            ['borrower'=>'stacksbuilder.btc','amount'=>'0.3000 sBTC','rate'=>12.0,'daysLeft'=>8,'status'=>'WARNING'],
        ];
    }

    public static function nfts(): array
    {
        return [
            ['id'=>1,'name'=>'Stacks Punk #4821','collection'=>'Stacks Punks','floor'=>1.2,'volume30d'=>45.8,'liquidity'=>'High','color1'=>'#6366f1','color2'=>'#06b6d4'],
            ['id'=>2,'name'=>'Bitcoin Frogs #312','collection'=>'Bitcoin Frogs','floor'=>0.8,'volume30d'=>28.3,'liquidity'=>'Medium','color1'=>'#10b981','color2'=>'#f59e0b'],
            ['id'=>3,'name'=>'Ordinal Maxi #99','collection'=>'Ordinal Maxis','floor'=>2.1,'volume30d'=>67.4,'liquidity'=>'High','color1'=>'#ef4444','color2'=>'#ec4899'],
        ];
    }

    public static function poolMetrics(): array
    {
        return [
            'totalLiquidity'  => '847,320',
            'poolApy'         => 8.2,
            'utilizationRate' => 67.4,
        ];
    }

    public static function vaultPosition(): array
    {
        return [
            'deposited'      => '10,000',
            'apy'            => 12.4,
            'accruedYield'   => '34.52',
            'projected90d'   => '305',
        ];
    }

    public static function loanHistory(): array
    {
        return [
            ['type'=>'Reputation Loan','amount'=>'0.5000 sBTC','rate'=>8,'duration'=>'30 days','status'=>'ACTIVE','date'=>'Mar 01, 2026'],
            ['type'=>'NFT Loan','amount'=>'0.3000 sBTC','rate'=>10,'duration'=>'14 days','status'=>'WARNING','date'=>'Mar 14, 2026'],
            ['type'=>'Reputation Loan','amount'=>'0.2000 sBTC','rate'=>9,'duration'=>'30 days','status'=>'REPAID','date'=>'Jan 15, 2026'],
            ['type'=>'Reputation Loan','amount'=>'0.8000 sBTC','rate'=>7,'duration'=>'60 days','status'=>'REPAID','date'=>'Nov 20, 2025'],
            ['type'=>'NFT Loan','amount'=>'1.0000 sBTC','rate'=>12,'duration'=>'14 days','status'=>'REPAID','date'=>'Sep 10, 2025'],
            ['type'=>'Reputation Loan','amount'=>'0.4000 sBTC','rate'=>15,'duration'=>'30 days','status'=>'DEFAULTED','date'=>'Jun 05, 2025'],
        ];
    }

    public static function vaultHistory(): array
    {
        return [
            ['action'=>'Deposit','amount'=>'10,000 USDCx','date'=>'Mar 01, 2026'],
            ['action'=>'Withdraw','amount'=>'2,000 USDCx','date'=>'Jan 10, 2026'],
            ['action'=>'Deposit','amount'=>'5,000 USDCx','date'=>'Dec 15, 2025'],
        ];
    }

    public static function reputationBreakdown(): array
    {
        return [
            ['signal'=>'Wallet Age','value'=>'847 days','points'=>210],
            ['signal'=>'BNS Ownership','value'=>'vilansh.btc','points'=>150],
            ['signal'=>'Transaction Activity','value'=>'1,247 txs','points'=>180],
            ['signal'=>'DeFi Interactions','value'=>'12 protocols','points'=>160],
            ['signal'=>'Repayment History','value'=>'4/4 repaid','points'=>80],
        ];
    }

    public static function chartData(): array
    {
        $labels = [];
        $yields = [];
        $apys   = [];
        for ($i = 90; $i >= 0; $i -= 3) {
            $labels[] = now()->subDays($i)->format('M d');
            $yields[] = round(305 * ((90 - $i) / 90), 2);
            $apys[]   = round(7.5 + (sin($i * 0.2) * 0.8), 1);
        }
        return ['labels' => $labels, 'yields' => $yields, 'apys' => $apys];
    }
}
