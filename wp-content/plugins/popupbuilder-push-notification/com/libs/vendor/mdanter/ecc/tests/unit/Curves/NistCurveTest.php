<?php

namespace Mdanter\Ecc\Tests;

use Mdanter\Ecc\Math\GmpMathInterface;
use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Crypto\Signature\Signature;
use Mdanter\Ecc\Crypto\Signature\Signer;
use Mdanter\Ecc\Random\RandomGeneratorFactory;
use Mdanter\Ecc\Random\RandomNumberGeneratorInterface;

class NistCurveTest extends AbstractTestCase
{
    /**
     * @dataProvider getAdapters
     * @testdox Test 192-bit generated curves against ANSI X9.62 specifications
     */
    public function testP192CurveAnsiX962ValidityTest(GmpMathInterface $math)
    {
        $generator = EccFactory::getNistCurves($math)->generator192();

        $d = gmp_init('651056770906015076056810763456358567190100156695615665659', 10);
        $Q = $generator->mul($d);

        $this->assertEquals($math->hexDec('0x62B12D60690CDCF330BABAB6E69763B471F994DD702D16A5'), $math->toString($Q->getX()));

        $k = gmp_init('6140507067065001063065065565667405560006161556565665656654', 10);
        $R = $generator->mul($k);
        $expected = EccFactory::getNistCurves($math)->curve192()->getPoint(
            gmp_init('0x885052380FF147B734C330C43D39B2C4A89F29B0F749FEAD', 16),
            gmp_init('0x9CF9FA1CBEFEFB917747A3BB29C072B9289C2547884FD835', 16)
        );

        $this->assertTrue($expected->equals($R));

        $u1 = gmp_init('2563697409189434185194736134579731015366492496392189760599', 10);
        $u2 = gmp_init('6266643813348617967186477710235785849136406323338782220568', 10);

        $temp = $generator->mul($u1)->add($Q->mul($u2));

        $this->assertEquals($math->hexDec('0x885052380FF147B734C330C43D39B2C4A89F29B0F749FEAD'), $math->toString($temp->getX()));
        $this->assertEquals($math->hexDec('0x9CF9FA1CBEFEFB917747A3BB29C072B9289C2547884FD835'), $math->toString($temp->getY()));
    }

    public function getB22Params()
    {
        return $this->_getAdapters(
            array(
                array('cd6d0f029a023e9aaca429615b8f577abee685d8257cc83a',
                      '0x00019c410987680e9fb6c0b6ecc01d9a2647c8bae27721bacdfc', false, ),
                array('00017f2fce203639e9eaf9fb50b81fc32776b30e3b02af16c73b',
                      '0x95da95c5e72dd48e229d4748d4eee658a9a54111b23b2adb', false, ),
                array('0x4f77f8bc7fccbadd5760f4938746d5f253ee2168c1cf2792',
                      '0x000147156ff824d131629739817edb197717c41aab5c2a70f0f6', false, ),
                array('0xc58d61f88d905293bcd4cd0080bcb1b7f811f2ffa41979f6',
                      '0x8804dc7a7c4c7f8b5d437f5156f3312ca7d6de8a0e11867f', true, ),
                array('0xcdf56c1aa3d8afc53c521adf3ffb96734a6a630a4a5b5a70',
                      '0x97c1c44a5fb229007b5ec5d25f7413d170068ffd023caa4e', true, ),
                array('0x89009c0dc361c81e99280c8e91df578df88cdf4b0cdedced',
                      '0x27be44a529b7513e727251f128b34262a0fd4d8ec82377b9', true, ),
                array('0x6a223d00bd22c52833409a163e057e5b5da1def2a197dd15',
                      '0x7b482604199367f1f303f9ef627f922f97023e90eae08abf', true, ),
                array('0x6dccbde75c0948c98dab32ea0bc59fe125cf0fb1a3798eda',
                      '0x0001171a3e0fa60cf3096f4e116b556198de430e1fbd330c8835', false, ),
                array('0xd266b39e1f491fc4acbbbc7d098430931cfa66d55015af12',
                      '0x193782eb909e391a3148b7764e6b234aa94e48d30a16dbb2', false, ),
                array('0x9d6ddbcd439baa0c6b80a654091680e462a7d1d3f1ffeb43',
                      '0x6ad8efc4d133ccf167c44eb4691c80abffb9f82b932b8caa', false, ),
                array('0x146479d944e6bda87e5b35818aa666a4c998a71f4e95edbc',
                      '0xa86d6fe62bc8fbd88139693f842635f687f132255858e7f6', false, ),
                array('0xe594d4a598046f3598243f50fd2c7bd7d380edb055802253',
                '0x509014c0c4d6b536e3ca750ec09066af39b4c8616a53a923',
                false,
                ),
            )
        );
    }

    /**
     * @dataProvider getB22Params
     * @testdox Test 192-bit generated curve points against ECDSAVS section B2.2 samples
     */
    public function testP192CurveEcdsavsB22PointValidityTest(GmpMathInterface $math, $x, $y, $expected)
    {
        $x = gmp_init($x, 16);
        $y = gmp_init($y, 16);

        $generator = EccFactory::getNistCurves($math)->generator192();

        $this->assertEquals($expected, $generator->isValid($x, $y));
    }

    public function getB24Params()
    {
        return $this->_getAdapters(
            array(
                array(
                    '0x84ce72aa8699df436059f052ac51b6398d2511e49631bcb7e71f89c499b9ee425dfbc13a5f6d408471b054f2655617cbbaf7937b7c80cd8865cf02c8487d30d2b0fbd8b2c4e102e16d828374bbc47b93852f212d5043c3ea720f086178ff798cc4f63f787b9c2e419efa033e7644ea7936f54462dc21a6c4580725f7f0e7d158',
                    '0xd9dbfb332aa8e5ff091e8ce535857c37c73f6250ffb2e7ac',
                    '0x282102e364feded3ad15ddf968f88d8321aa268dd483ebc4',
                    '0x64dca58a20787c488d11d6dd96313f1b766f2d8efe122916',
                    '0x1ecba28141e84ab4ecad92f56720e2cc83eb3d22dec72479', true, ),
                array(
                    '0x94bb5bacd5f8ea765810024db87f4224ad71362a3c28284b2b9f39fab86db12e8beb94aae899768229be8fdb6c4f12f28912bb604703a79ccff769c1607f5a91450f30ba0460d359d9126cbd6296be6d9c4bb96c0ee74cbb44197c207f6db326ab6f5a659113a9034e54be7b041ced9dcf6458d7fb9cbfb2744d999f7dfd63f4',
                    '0x3e53ef8d3112af3285c0e74842090712cd324832d4277ae7',
                    '0xcc75f8952d30aec2cbb719fc6aa9934590b5d0ff5a83adb7',
                    '0x8285261607283ba18f335026130bab31840dcfd9c3e555af',
                    '0x356d89e1b04541afc9704a45e9c535ce4a50929e33d7e06c', true, ),
                array(
                    '0xf6227a8eeb34afed1621dcc89a91d72ea212cb2f476839d9b4243c66877911b37b4ad6f4448792a7bbba76c63bdd63414b6facab7dc71c3396a73bd7ee14cdd41a659c61c99b779cecf07bc51ab391aa3252386242b9853ea7da67fd768d303f1b9b513d401565b6f1eb722dfdb96b519fe4f9bd5de67ae131e64b40e78c42dd',
                    '0x16335dbe95f8e8254a4e04575d736befb258b8657f773cb7',
                    '0x421b13379c59bc9dce38a1099ca79bbd06d647c7f6242336',
                    '0x4141bd5d64ea36c5b0bd21ef28c02da216ed9d04522b1e91',
                    '0x159a6aa852bcc579e821b7bb0994c0861fb08280c38daa09', false, ),
                array(
                    '0x16b5f93afd0d02246f662761ed8e0dd9504681ed02a253006eb36736b563097ba39f81c8e1bce7a16c1339e345efabbc6baa3efb0612948ae51103382a8ee8bc448e3ef71e9f6f7a9676694831d7f5dd0db5446f179bcb737d4a526367a447bfe2c857521c7f40b6d7d7e01a180d92431fb0bbd29c04a0c420a57b3ed26ccd8a',
                    '0xfd14cdf1607f5efb7b1793037b15bdf4baa6f7c16341ab0b',
                    '0x83fa0795cc6c4795b9016dac928fd6bac32f3229a96312c4',
                    '0x8dfdb832951e0167c5d762a473c0416c5c15bc1195667dc1',
                    '0x1720288a2dc13fa1ec78f763f8fe2ff7354a7e6fdde44520', false, ),
                array(
                    '0x08a2024b61b79d260e3bb43ef15659aec89e5b560199bc82cf7c65c77d39192e03b9a895d766655105edd9188242b91fbde4167f7862d4ddd61e5d4ab55196683d4f13ceb90d87aea6e07eb50a874e33086c4a7cb0273a8e1c4408f4b846bceae1ebaac1b2b2ea851a9b09de322efe34cebe601653efd6ddc876ce8c2f2072fb',
                    '0x674f941dc1a1f8b763c9334d726172d527b90ca324db8828',
                    '0x65adfa32e8b236cb33a3e84cf59bfb9417ae7e8ede57a7ff',
                    '0x9508b9fdd7daf0d8126f9e2bc5a35e4c6d800b5b804d7796',
                    '0x36f2bf6b21b987c77b53bb801b3435a577e3d493744bfab0', false, ),
                array(
                    '0x1843aba74b0789d4ac6b0b8923848023a644a7b70afa23b1191829bbe4397ce15b629bf21a8838298653ed0c19222b95fa4f7390d1b4c844d96e645537e0aae98afb5c0ac3bd0e4c37f8daaff25556c64e98c319c52687c904c4de7240a1cc55cd9756b7edaef184e6e23b385726e9ffcba8001b8f574987c1a3fedaaa83ca6d',
                    '0x10ecca1aad7220b56a62008b35170bfd5e35885c4014a19f',
                    '0x04eb61984c6c12ade3bc47f3c629ece7aa0a033b9948d686',
                    '0x82bfa4e82c0dfe9274169b86694e76ce993fd83b5c60f325',
                    '0xa97685676c59a65dbde002fe9d613431fb183e8006d05633', false, ),
                array(
                    '0x5a478f4084ddd1a7fea038aa9732a822106385797d02311aeef4d0264f824f698df7a48cfb6b578cf3da416bc0799425bb491be5b5ecc37995b85b03420a98f2c4dc5c31a69a379e9e322fbe706bbcaf0f77175e05cbb4fa162e0da82010a278461e3e974d137bc746d1880d6eb02aa95216014b37480d84b87f717bb13f76e1',
                    '0x6636653cb5b894ca65c448277b29da3ad101c4c2300f7c04',
                    '0xfdf1cbb3fc3fd6a4f890b59e554544175fa77dbdbeb656c1',
                    '0xeac2ddecddfb79931a9c3d49c08de0645c783a24cb365e1c',
                    '0x3549fee3cfa7e5f93bc47d92d8ba100e881a2a93c22f8d50', false, ),
                array(
                    '0xc598774259a058fa65212ac57eaa4f52240e629ef4c310722088292d1d4af6c39b49ce06ba77e4247b20637174d0bd67c9723feb57b5ead232b47ea452d5d7a089f17c00b8b6767e434a5e16c231ba0efa718a340bf41d67ea2d295812ff1b9277daacb8bc27b50ea5e6443bcf95ef4e9f5468fe78485236313d53d1c68f6ba2',
                    '0xa82bd718d01d354001148cd5f69b9ebf38ff6f21898f8aaa',
                    '0xe67ceede07fc2ebfafd62462a51e4b6c6b3d5b537b7caf3e',
                    '0x4d292486c620c3de20856e57d3bb72fcde4a73ad26376955',
                    '0xa85289591a6081d5728825520e62ff1c64f94235c04c7f95', false, ),
                array(
                    '0xca98ed9db081a07b7557f24ced6c7b9891269a95d2026747add9e9eb80638a961cf9c71a1b9f2c29744180bd4c3d3db60f2243c5c0b7cc8a8d40a3f9a7fc910250f2187136ee6413ffc67f1a25e1c4c204fa9635312252ac0e0481d89b6d53808f0c496ba87631803f6c572c1f61fa049737fdacce4adff757afed4f05beb658',
                    '0x7d3b016b57758b160c4fca73d48df07ae3b6b30225126c2f',
                    '0x4af3790d9775742bde46f8da876711be1b65244b2b39e7ec',
                    '0x95f778f5f656511a5ab49a5d69ddd0929563c29cbc3a9e62',
                    '0x75c87fc358c251b4c83d2dd979faad496b539f9f2ee7a289', false, ),
                array(
                    '0x31dd9a54c8338bea06b87eca813d555ad1850fac9742ef0bbe40dad400e10288acc9c11ea7dac79eb16378ebea9490e09536099f1b993e2653cd50240014c90a9c987f64545abc6a536b9bd2435eb5e911fdfde2f13be96ea36ad38df4ae9ea387b29cced599af777338af2794820c9cce43b51d2112380a35802ab7e396c97a',
                    '0x9362f28c4ef96453d8a2f849f21e881cd7566887da8beb4a',
                    '0xe64d26d8d74c48a024ae85d982ee74cd16046f4ee5333905',
                    '0xf3923476a296c88287e8de914b0b324ad5a963319a4fe73b',
                    '0xf0baeed7624ed00d15244d8ba2aede085517dbdec8ac65f5', true, ),
                array(
                    '0xb2b94e4432267c92f9fdb9dc6040c95ffa477652761290d3c7de312283f6450d89cc4aabe748554dfb6056b2d8e99c7aeaad9cdddebdee9dbc099839562d9064e68e7bb5f3a6bba0749ca9a538181fc785553a4000785d73cc207922f63e8ce1112768cb1de7b673aed83a1e4a74592f1268d8e2a4e9e63d414b5d442bd0456d',
                    '0xcc6fc032a846aaac25533eb033522824f94e670fa997ecef',
                    '0xe25463ef77a029eccda8b294fd63dd694e38d223d30862f1',
                    '0x066b1d07f3a40e679b620eda7f550842a35c18b80c5ebe06',
                    '0xa0b0fb201e8f2df65e2c4508ef303bdc90d934016f16b2dc', false, ),
                array(
                    '0x4366fcadf10d30d086911de30143da6f579527036937007b337f7282460eae5678b15cccda853193ea5fc4bc0a6b9d7a31128f27e1214988592827520b214eed5052f7775b750b0c6b15f145453ba3fee24a085d65287e10509eb5d5f602c440341376b95c24e5c4727d4b859bfe1483d20538acdd92c7997fa9c614f0f839d7',
                    '0x955c908fe900a996f7e2089bee2f6376830f76a19135e753',
                    '0xba0c42a91d3847de4a592a46dc3fdaf45a7cc709b90de520',
                    '0x1f58ad77fc04c782815a1405b0925e72095d906cbf52a668',
                    '0xf2e93758b3af75edf784f05a6761c9b9a6043c66b845b599', false, ),
                array(
                    '0x543f8af57d750e33aa8565e0cae92bfa7a1ff78833093421c2942cadf9986670a5ff3244c02a8225e790fbf30ea84c74720abf99cfd10d02d34377c3d3b41269bea763384f372bb786b5846f58932defa68023136cd571863b304886e95e52e7877f445b9364b3f06f3c28da12707673fecb4b8071de06b6e0a3c87da160cef3',
                    '0x31f7fa05576d78a949b24812d4383107a9a45bb5fccdd835',
                    '0x8dc0eb65994a90f02b5e19bd18b32d61150746c09107e76b',
                    '0xbe26d59e4e883dde7c286614a767b31e49ad88789d3a78ff',
                    '0x8762ca831c1ce42df77893c9b03119428e7a9b819b619068', false, ),
                array(
                    '0xd2e8454143ce281e609a9d748014dcebb9d0bc53adb02443a6aac2ffe6cb009f387c346ecb051791404f79e902ee333ad65e5c8cb38dc0d1d39a8dc90add5023572720e5b94b190d43dd0d7873397504c0c7aef2727e628eb6a74411f2e400c65670716cb4a815dc91cbbfeb7cfe8c929e93184c938af2c078584da045e8f8d1',
                    '0x66aa8edbbdb5cf8e28ceb51b5bda891cae2df84819fe25c0',
                    '0x0c6bc2f69030a7ce58d4a00e3b3349844784a13b8936f8da',
                    '0xa4661e69b1734f4a71b788410a464b71e7ffe42334484f23',
                    '0x738421cf5e049159d69c57a915143e226cac8355e149afe9', false, ),
                array(
                    '0x6660717144040f3e2f95a4e25b08a7079c702a8b29babad5a19a87654bc5c5afa261512a11b998a4fb36b5d8fe8bd942792ff0324b108120de86d63f65855e5461184fc96a0a8ffd2ce6d5dfb0230cbbdd98f8543e361b3205f5da3d500fdc8bac6db377d75ebef3cb8f4d1ff738071ad0938917889250b41dd1d98896ca06fb',
                    '0xbcfacf45139b6f5f690a4c35a5fffa498794136a2353fc77',
                    '0x6f4a6c906316a6afc6d98fe1f0399d056f128fe0270b0f22',
                    '0x9db679a3dafe48f7ccad122933acfe9da0970b71c94c21c1',
                    '0x984c2db99827576c0a41a5da41e07d8cc768bc82f18c9da9', false, ),
            )
        );
    }

    /**
     * @dataProvider getB24Params
     * @testdox Test 192-bit generated curve points against ECDSAVS section B2.4 samples
     */
    public function testP192CurveEcdsavsB24SignatureValidityTest(GmpMathInterface $math, $msg, $Qx, $Qy, $R, $S, $expected)
    {
        $msg = gmp_init($msg, 16);
        $Qx = gmp_init($Qx, 16);
        $Qy = gmp_init($Qy, 16);
        $R = gmp_init($R, 16);
        $S = gmp_init($S, 16);

        $generator = EccFactory::getNistCurves($math)->generator192();

        $publicKey = $generator->getPublicKeyFrom($Qx, $Qy);
        $signer = new Signer($math);

        $actual = $signer->verify($publicKey, new Signature($R, $S), $math->digestInteger($msg));

        $this->assertEquals($expected, $actual);
    }

    public function getSignatureValidityAdapters()
    {
        return $this->_getAdapters(
            array(array(array(
                'd' => '651056770906015076056810763456358567190100156695615665659',
                'k' => '6140507067065001063065065565667405560006161556565665656654',
                'e' => '968236873715988614170569073515315707566766479517',
                'R' => '3342403536405981729393488334694600415596881826869351677613',
                'S' => '5735822328888155254683894997897571951568553642892029982342'
            )))
        );
    }

    /**
     * @dataProvider getSignatureValidityAdapters
     * @testdox Valid hashes are correctly validated.
     */
    public function testSignatureValidityWithCorrectHash(GmpMathInterface $math, array $values)
    {
        $generator = EccFactory::getNistCurves($math)->generator192();

        $privateKey = $generator->getPrivateKeyFrom(gmp_init($values['d'], 10));
        $publicKey = $privateKey->getPublicKey();
        $signer = new Signer($math);

        $sig = $signer->sign($privateKey, gmp_init($values['e'], 10), gmp_init($values['k'], 10));

        $this->assertEquals($values['R'], $math->toString($sig->getR()));
        $this->assertEquals($values['S'], $math->toString($sig->getS()));

        $this->assertTrue($signer->verify($publicKey, $sig, gmp_init($values['e'], 10)));
    }

    /**
     * @dataProvider getSignatureValidityAdapters
     * @testdox Forged hashes are correctly rejected.
     */
    public function testSignatureValidityWithForgedHash(GmpMathInterface $math, array $values)
    {
        $generator = EccFactory::getNistCurves($math)->generator192();

        $privateKey = $generator->getPrivateKeyFrom(gmp_init($values['d'], 10));
        $publicKey = $privateKey->getPublicKey();
        $signer = new Signer($math);

        $sig = $signer->sign($privateKey, gmp_init($values['e'], 10), gmp_init($values['k'], 10));

        $this->assertEquals($values['R'], $math->toString($sig->getR()));
        $this->assertEquals($values['S'], $math->toString($sig->getS()));

        $this->assertFalse($signer->verify($publicKey, $sig, $math->sub(gmp_init($values['e'], 10), gmp_init(1, 10))));
    }

    public function getAdaptersWithRand()
    {
        $rngs = array(
            array(RandomGeneratorFactory::getRandomGenerator())
        );
        
        return $this->_getAdapters($rngs);
    }

    /**
     * @dataProvider getAdaptersWithRand
     */
    public function testSignatureValidityWithGeneratedKeys(GmpMathInterface $math, RandomNumberGeneratorInterface $rng)
    {
        $generator = EccFactory::getNistCurves($math)->generator192();

        $signer = new Signer($math);

        $privateKey = $generator->createPrivateKey();
        $publicKey = $privateKey->getPublicKey();
        $randomK = $rng->generate($privateKey->getPoint()->getOrder());

        $hash = $rng->generate($generator->getOrder());
        $signature = $signer->sign($privateKey, $hash, $randomK);

        $this->assertTrue($signer->verify($publicKey, $signature, $hash), 'Correctly validates valid hash.');
        $this->assertFalse($signer->verify($publicKey, $signature, $math->sub($hash, gmp_init(1, 10))), 'Correctly rejects tampered hash.');
    }

    /**
     * @dataProvider getAdaptersWithRand
     * @testdox Test Diffie-Hellman key exchange and message encryption/decryption
     */
    public function testDiffieHellman(GmpMathInterface $math, RandomNumberGeneratorInterface $rng)
    {
        $generator = EccFactory::getNistCurves($math)->generator192($rng);
        $alicePrivKey = $generator->createPrivateKey();
        $bobPrivKey = $generator->createPrivateKey();

        $alice = $alicePrivKey->createExchange($bobPrivKey->getPublicKey());
        $bob = $bobPrivKey->createExchange($alicePrivKey->getPublicKey());

        $this->assertTrue($math->equals($alice->calculateSharedKey(), $bob->calculateSharedKey()));
    }
}
